<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserDetails;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class WithdrawInfoController extends Controller
{

    //User
    public function withdrawPage()
    {
        $detail = \App\ProfileStore::where('id', 1)->first();
        $user = \App\UserDetails::where('id', \Session::get('user.id'))->first();
        $remainingTxn = \App\TransactionDetail::where([['userid', \Session::get('user.id')], ['txntype', 1], ['paymentstatus', '<', 2], ['planid', 0]])->first();
        if (is_null($remainingTxn))
            $remainingTxn = null;

        $caiPrice = $detail ? (float) $detail->price : 1.0;
        if ($caiPrice <= 0)
            $caiPrice = 1.0;

        $roiRemainingUsdt = (float) $user->stackingIncome()->where('status', 0)->sum('remaining_usdt');
        $roiRemainingCai = (float) $user->stackingIncome()->where('status', 0)->sum('remaining');
        // if ($roiRemainingCai <= 0 && $roiRemainingUsdt > 0) {
        //     $roiRemainingCai = $roiRemainingUsdt / $caiPrice;
        // }
        $roiUsdDynamic = $roiRemainingCai * $caiPrice;

        $workingRemainingUsdt = (float) ($user->levelIncome()->where('status', 0)->sum('remaining_usdt') + $user->bonusReward()->where('status', '!=', 3)->sum('remaining_usdt') + $user->clubIncome()->where('status', 0)->sum('remaining_usdt') + $user->lifetimeIncome()->where('status', 0)->sum('remaining'));

        return view('user.withdrawrequest')
            ->with('detail', $detail)
            ->with('user', $user)
            ->with('remaining', $remainingTxn)
            ->with('roiRemainingUsdt', $roiRemainingUsdt)
            ->with('roiRemainingCai', $roiRemainingCai)
            ->with('roiUsdDynamic', $roiUsdDynamic)
            ->with('workingRemainingUsdt', $workingRemainingUsdt)
            ->with('caiPrice', $caiPrice);
    }

    public function withdrawRequest(Request $request)
    {
        set_time_limit(0);

        $unlockTime = strtotime('2026-09-10 22:00:00');
        if (time() < $unlockTime) {
            return redirect()->to('/User/WithdrawRequest')->with('warning', 'All withdrawals (ROI & Working Incomes) are temporarily disabled until 10:00 PM, 10 Sep 2026.');
        }

        $profile = \App\ProfileStore::where('id', 1)->first();
        if ($profile->usdtbep20_withdrawal_status == 0) {
            return redirect()->back()->with('warning', 'Withdrawal method is not available, please try again later');
        }

        $withdrawType = $request->input('withdraw_type', 'roi'); // 'roi' or 'working'

        $remainingTxn = \App\TransactionDetail::where([['userid', \Session::get('user.id')], ['txntype', 1], ['paymentstatus', '<', 2], ['planid', 0]])->orderBy('id', 'asc')->first();
        if (is_null($remainingTxn)) {
            $valid = Validator::make($request->all(), [
                'currency' => ['required', 'string'],
                'amount' => ['nullable', 'numeric'],
                'amountusdt' => ['required', 'numeric'],
                'withdraw_type' => ['nullable', 'string'],
            ])->validate();
            if (is_null($request->honeypotu) || $request->honeypotu == '') {
                return redirect()->back()->with('warning', 'There is some issue. Please try again.');
            }
            $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

            if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
                return redirect('/User/WithdrawRequest')->with('warning', 'Conversion not permitted. Please contact Admin');
            }
            if (fmod($request->amountusdt, 10) != 0) {
                return redirect('/User/WithdrawRequest')->with('warning', 'Amount should be multiple of 10$.');
            }
            $silverAmount = $userDetail->stackingDeposite()->where('staketype', 4)->sum('usdt');
            if ($silverAmount > 0 && !in_array(date('d'), [10, 20, 30])) {
                return redirect('/User/WithdrawRequest')->with('warning', 'Withdrawal available on 10,20,30th of every month.');
            }

            $pendingWithdrawal = \App\TransactionDetail::where([['paymentstatus', '<', 2], ['txntype', 1], ['userid', \Session::get('user.id')]])->orderBy('id', 'desc')->get();
            if (count($pendingWithdrawal)) {
                return redirect()->back()->with('warning', 'You already have a pending withdrawal request. Please wait until it is cleared.');
            }

            $previousWithdrawal = \App\TransactionDetail::where([['paymentstatus', '<=', 2], ['txntype', 1], ['created_at', '>', date('Y-m-d H:i:s', strtotime('-4 hours', strtotime(now())))], ['userid', \Session::get('user.id')]])->orderBy('id', 'desc')->get();
            $amountWith = ($request->amountusdt) + $previousWithdrawal->sum('amountusdt');

            $request->amount = $request->amountusdt / $profile->price;

            // Separate balance check according to withdrawal type
            $roiBalance = (float) $userDetail->stackingIncome()->where('status', 0)->sum('remaining_usdt');
            $workingBalance = (float) ($userDetail->levelIncome()->where('status', 0)->sum('remaining_usdt') + $userDetail->bonusReward()->where('status', '!=', 3)->sum('remaining_usdt') + $userDetail->clubIncome()->where('status', 0)->sum('remaining_usdt') + $userDetail->lifetimeIncome()->where('status', 0)->sum('remaining'));

            if ($withdrawType == 'roi') {
                $roiUnlockTime = strtotime('2026-09-10 22:00:00');
                if (time() < $roiUnlockTime) {
                    return redirect()->to('/User/WithdrawRequest')->with('warning', 'ROI withdrawal is disabled till 10:00 PM, 10 Sep 2026. Only Working Income withdrawal is available currently.');
                }
                if ($roiBalance < $request->amountusdt) {
                    return redirect()->to('/User/WithdrawRequest')->with('warning', 'Insufficient Staking ROI balance. Available: $' . number_format($roiBalance, 2));
                }
            } else {
                if ($workingBalance < $request->amountusdt) {
                    return redirect()->to('/User/WithdrawRequest')->with('warning', 'Insufficient Working Income balance. Available: $' . number_format($workingBalance, 2));
                }
            }

            if ($request->amountusdt < 10) {
                return redirect()->back()->with('warning', 'Minimum withdraw amount is 10 $ and Your requested USDT equals to ' . $request->amountusdt . ' $');
            }
            if ($amountWith > 50) {
                return redirect()->back()->with('warning', 'Maximum withdraw amount is 50 $ and Your requested Withdrawal in last 4 hours is ' . $previousWithdrawal->sum('amountusdt') . ' $');
            }

            if (!is_null($userDetail->assetDetail())) {
                \DB::beginTransaction();
                try {
                    if ($request->currency == 'usdt') {
                        $addr = $userDetail->assetDetail()->usdttrc20addr;
                    } elseif ($request->currency == 'usdtbep20') {
                        $addr = $userDetail->assetDetail()->usdtbep20addr;
                    }
                    $randId = rand(111111, 999999);
                    $txnId = $this->insertWithdrawEntry(\Session::get('user.id'), $request->amount, $request->amountusdt, $addr, $request->currency, $randId, $withdrawType);
                    $data['token'] = $randId;
                    $data['email'] = $userDetail->user()->email;
                    $data['view'] = 'withdrawOtpMail';
                    $data['subject'] = 'OTP to confirm withdraw request.';
                    $data['useruuid'] = $userDetail->user()->uuid;
                    $data['currency'] = $request->currency;
                    $data['address'] = $addr;
                    $data['amountusdt'] = $request->amountusdt;
                    $data['amountcoin'] = $request->amount;
                    $mailSend = new SupportQueryController();
                    $mailStatus = $mailSend->sendMailgun($data);
                    $txnIdUpdate = \App\TransactionDetail::where('id', $txnId)->update([
                        'b_status' => ($mailStatus == 0) ? 1 : 2,
                    ]);
                    \DB::commit();
                    return redirect('/User/WithdrawRequest')->with('success', 'A mail containing OTP sent to your registered mail id. Check mail and insert OTP to confirm withdraw request and process withdraw.');
                } catch (Exception $e) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                    return redirect('/User/WithdrawRequest')->with('warning', 'You have some issue with withdraw. Please Try again.');
                }
            } else {
                return redirect('/User/EditProfile')->with('warning', 'Please update wallet address first.');
            }
        } else {
            $valid = Validator::make($request->all(), [
                'otp' => ['required', 'numeric'],
            ])->validate();
            $txnInfo = \App\TransactionInfo::where('txnid', $remainingTxn->id)->first();
            if ($request->otp == $txnInfo->transaction_hash) {

                $reducePool = 0;
                $reduceCps = 0;
                $reduceLevel = 0;
                $reduceReward = 0;
                \DB::beginTransaction();
                try {
                    $txnId = $remainingTxn->id;
                    $updateTxn = \App\TransactionDetail::where('id', $remainingTxn->id)->update([
                        'planid' => 1,
                        'b_status' => 0,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $updateTransactionInfo = \App\TransactionInfo::where('txnid', $remainingTxn->id)->update([
                        'transaction_hash' => '',
                    ]);

                    $amt = $remainingTxn->amountusdt;
                    $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();
                    $withdrawType = $remainingTxn->comments ?: 'roi';

                    if ($withdrawType == 'roi' || str_contains(strtolower($remainingTxn->txndesc), 'roi')) {
                        // 1. Strictly reduce from Staking ROI (CPS)
                        $entry = $userDetail->stackingIncome()->where('remaining_usdt', '>', 0)->where('status', '!=', 3);
                        $reduceCps = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
                        foreach ($entry as $cps) {
                            if ($amt > 0) {
                                $deductUsdt = min($amt, (float) $cps->remaining_usdt);
                                $deductCai = ($profile->price > 0) ? ($deductUsdt / $profile->price) : $deductUsdt;
                                \App\CpsIncome::where('id', $cps->id)->update([
                                    'remaining_usdt' => max(0, $cps->remaining_usdt - $deductUsdt),
                                    'remaining' => max(0, $cps->remaining - $deductCai),
                                    ($cps->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                ]);
                                $amt -= $deductUsdt;
                            } else {
                                break;
                            }
                        }
                    } else {
                        // 2. Working Income: Reduce from Level + Direct Bonus + Club + Lifetime/Achievement
                        // (A) Level Income
                        $entry = $userDetail->levelIncome()->where('remaining_usdt', '>', 0)->where('status', '!=', 3);
                        $reduceLevel = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
                        foreach ($entry as $level) {
                            if ($amt > 0) {
                                $deductUsdt = min($amt, (float) $level->remaining_usdt);
                                \App\LevelIncome::where('id', $level->id)->update([
                                    'remaining_usdt' => max(0, $level->remaining_usdt - $deductUsdt),
                                    ($level->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                ]);
                                $amt -= $deductUsdt;
                            } else {
                                break;
                            }
                        }

                        // (B) Direct Bonus (bonus_rewards)
                        if ($amt > 0) {
                            $entry = $userDetail->bonusReward()->where('remaining_usdt', '>', 0)->where('status', '!=', 3);
                            $reduceReward = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
                            foreach ($entry as $bonus) {
                                if ($amt > 0) {
                                    $deductUsdt = min($amt, (float) $bonus->remaining_usdt);
                                    \App\BonusReward::where('id', $bonus->id)->update([
                                        'remaining_usdt' => max(0, $bonus->remaining_usdt - $deductUsdt),
                                        ($bonus->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                    ]);
                                    $amt -= $deductUsdt;
                                } else {
                                    break;
                                }
                            }
                        }

                        // (C) Club / Pool Income
                        if ($amt > 0) {
                            $entry = $userDetail->clubIncome()->where('remaining_usdt', '>', 0)->where('status', '!=', 3);
                            $reducePool = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
                            foreach ($entry as $club) {
                                if ($amt > 0) {
                                    $deductUsdt = min($amt, (float) $club->remaining_usdt);
                                    \App\ClubIncome::where('id', $club->id)->update([
                                        'remaining_usdt' => max(0, $club->remaining_usdt - $deductUsdt),
                                        ($club->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                    ]);
                                    $amt -= $deductUsdt;
                                } else {
                                    break;
                                }
                            }
                        }

                        // (D) Achievement / Lifetime Income
                        if ($amt > 0) {
                            $entry = $userDetail->lifetimeIncome()->where('remaining', '>', 0)->where('status', 0);
                            foreach ($entry as $ach) {
                                if ($amt > 0) {
                                    $deductUsdt = min($amt, (float) $ach->remaining);
                                    \App\AchievementIncome::where('id', $ach->id)->update([
                                        'remaining' => max(0, $ach->remaining - $deductUsdt),
                                        ($ach->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                    ]);
                                    $amt -= $deductUsdt;
                                } else {
                                    break;
                                }
                            }
                        }
                    }

                    if ($amt > 0) {
                        \DB::rollback();
                        \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is income less than withdraw.');
                        return redirect()->back()->with('warning', 'You have some issue with withdraw. Please contact to admin.');
                    } else {
                        $withdrawInfoInsert = \App\WithdrawInfo::create([
                            'txnid' => $txnId,
                            'stacking' => $reduceCps,
                            'level' => $reduceLevel,
                            'bonus' => $reduceReward,
                            'club' => $reducePool,
                        ]);

                        $updateTxn = \App\TransactionDetail::where('id', $remainingTxn->id)->update([
                            'b_status' => 3,
                            'planid' => 2,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    \DB::commit();
                    return redirect('/User/WithdrawRequest')->with('success', 'Withdraw request of $' . number_format($remainingTxn->amountusdt, 2) . ' submitted successfully!');
                } catch (\Exception $e) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                    return redirect('/User/WithdrawRequest')->with('warning', 'You have some issue with withdraw. Please Try again.');
                }
            } else {
                return redirect('/User/WithdrawRequest')->with('warning', 'OTP is mismatch. Please try again.');
            }
        }
    }

    public function insertWithdrawEntry($userId, $styamount, $amount, $addr, $paymode, $randId, $withdrawType = 'roi')
    {
        $insTxn = \App\TransactionDetail::insertGetId([
            'userid' => $userId,
            'txntype' => 1,
            'amountsftc' => ($styamount),
            'amountusdt' => ($amount),
            'remaining' => ($amount),
            'paymentstatus' => 1,
            'txndesc' => ($withdrawType == 'roi' ? 'Staking ROI Withdrawal' : 'Working Income Withdrawal'),
            'currency' => $paymode,
            'comments' => $withdrawType,
            'paidby' => 0,
            'release_date' => date('Y-m-d'),
            'deduction' => ($amount * .10),/*($amount*.05)*/
            'net_amount' => ($amount * .90),/*($amount*.95)*/
            'planid' => 0,
            'plan_status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $transactionInfo = \App\TransactionInfo::create([
            'txnid' => $insTxn,
            'payment_addr' => $addr,
            'payee_addr' => '',
            'transaction_hash' => $randId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $insTxn;
    }

    public function withdrawApiUsdt($address, $amount)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://motomotanbbnjjbjjhjserver64sdsds.5kakar.com/api/withdraw',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
              "recipientAddress": "' . $address . '",
              "amount": "' . $amount . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            /*CURLOPT_POSTFIELDS => 'recipientAddress='.$address.'&amount='.$amount,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/x-www-form-urlencoded'
            ),*/
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        \Log::info($response);

        //\Log::info(json_decode($response)->Status);
        return json_decode($response);

    }

    public function isHTML($text)
    {
        $processed = htmlentities($text);
        if ($processed == $text)
            return false;
        return true;
    }

    public function withdrawLifetimeIncome(Request $request)
    {
        //return redirect('/User/LifetimeAchievementReward')->with('warning','We are currently upgrading the system. Please try again later to claim your rewards.');

        $valid = Validator::make($request->all(), [
            'currency' => ['required', 'string'],
            /*'password'  =>  ['required'],*/
        ])->validate();

        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

        if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
            return redirect('/User/LifetimeAchievementReward')->with('warning', 'Conversion not permitted. Please contact Admin');
        }
        if (sizeof($userDetail->lifetimeIncome()) && ($userDetail->lifetimeIncome()->where('remaining', '>', 0)->sum('remaining') > 0)) {
            if (1 == 1/*!is_null($userDetail->assetDetail())  && !is_null($userDetail->assetDetail()->usdtbep20addr)*/) {
                $reduceSalary = 0;
                $amount = $userDetail->lifetimeIncome()->where('status', 0)->sum('remaining');
                if ($request->currency == 'wallet') {
                    $deduction = 0;
                    $netamount = $amount;
                } else {
                    if (is_null($userDetail->assetDetail()) || is_null($userDetail->assetDetail()->usdtbep20addr)) {
                        return redirect('/User/LifetimeAchievementReward')->with('warning', 'Please Update Your Address');
                    }
                    $deduction = ($amount * .10);
                    $netamount = ($amount * .90);
                }
                \DB::beginTransaction();
                try {
                    $addr = $userDetail->assetDetail()->usdtbep20addr;

                    $insTxn = \App\TransactionDetail::insertGetId([
                        'userid' => $userDetail->id,
                        'txntype' => 1,
                        'amountsftc' => 0,
                        'amountusdt' => ($amount),
                        'remaining' => ($amount),
                        'paymentstatus' => 1,
                        'txndesc' => 'Withdrawal',
                        'currency' => $request->currency,
                        'comments' => $request->currency,
                        'paidby' => 0,
                        'release_date' => date('Y-m-d'),
                        'deduction' => ($deduction),/*($amount*.05)*/
                        'net_amount' => ($netamount),/*($amount*.95)*/
                        'planid' => 2,
                        'b_status' => 3,
                        'plan_status' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $transactionInfo = \App\TransactionInfo::create([
                        'txnid' => $insTxn,
                        'payment_addr' => $addr,
                        'payee_addr' => 'Lifetime Reward',
                        'transaction_hash' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    $amt = $amount;

                    $entry = $userDetail->lifetimeIncome()->where('remaining', '>', 0)->where('status', 0);
                    $reduceSalary = ($amt > $entry->sum('remaining')) ? $entry->sum('remaining') : $amt;
                    \Log::info('reduce Lifetime ' . $reduceSalary);
                    foreach ($entry as $lifetime) {
                        if ($amt > 0) {
                            $updLifetime = \App\AchievementIncome::where('id', $lifetime->id)->update([
                                'remaining' => (($amt > $lifetime->remaining) ? 0 : ($lifetime->remaining - $amt)),
                                ($lifetime->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn,
                                'status' => 1
                            ]);
                            $amt -= $lifetime->remaining;
                        } else {
                            break;
                        }
                        \Log::info('remaining after lifetime ' . $amt);
                    }
                    if ($amt > 0) {
                        \DB::rollback();
                        \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is income less than withdraw.');
                        return redirect()->back()->with('warning', 'You have some issue with withdraw. Please contact to admin.');
                    } else {
                        $withdrawInfoInsert = \App\WithdrawInfo::create([
                            'txnid' => $insTxn,
                            'salary' => $reduceSalary,
                        ]);
                    }

                    \DB::commit();
                    return redirect('/User/LifetimeAchievementReward')->with('success', 'Your withdrawal request has been processed. It will be credited to your wallet within 24 hours.');
                } catch (Exception $e) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                    return redirect()->back()->with('warning', 'You have some issue with withdraw. Please Try again.');
                }

            } else {
                return redirect('/User/EditProfile')->with('warning', 'Please update wallet address first.');
            }
        } else {
            return redirect('/User/LifetimeAchievementReward')->with('warning', 'You do not have any amount to withdraw.');
        }


    }




    public function withdrawClubIncome(Request $request)
    {
        //return redirect('/User/ClubReward')->with('warning','We are currently upgrading the system. Please try again later to claim your rewards.');

        $valid = Validator::make($request->all(), [
            'currency' => ['required', 'string'],
            /*'password'  =>  ['required'],*/
        ])->validate();

        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

        if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
            return redirect('/User/ClubReward')->with('warning', 'Conversion not permitted. Please contact Admin');
        }
        if (sizeof($userDetail->clubIncome()) && ($userDetail->clubIncome()->where('remaining_usdt', '>', 0)->sum('remaining_usdt') > 0)) {
            if (1 == 1/*!is_null($userDetail->assetDetail())  && !is_null($userDetail->assetDetail()->usdtbep20addr)*/) {
                $reduceSalary = 0;
                $amount = $userDetail->clubIncome()->where('status', 0)->sum('remaining_usdt');
                if ($request->currency == 'wallet') {
                    $deduction = 0;
                    $netamount = $amount;
                } else {
                    if (is_null($userDetail->assetDetail()) || is_null($userDetail->assetDetail()->usdtbep20addr)) {
                        return redirect('/User/LifetimeAchievementReward')->with('warning', 'Please Update Your Address');
                    }
                    $deduction = ($amount * .10);
                    $netamount = ($amount * .90);
                }
                \DB::beginTransaction();
                try {
                    $addr = $userDetail->assetDetail()->usdtbep20addr;

                    $insTxn = \App\TransactionDetail::insertGetId([
                        'userid' => $userDetail->id,
                        'txntype' => 1,
                        'amountsftc' => 0,
                        'amountusdt' => ($amount),
                        'remaining' => ($amount),
                        'paymentstatus' => 1,
                        'txndesc' => 'Withdrawal',
                        'currency' => $request->currency,
                        'comments' => $request->currency,
                        'paidby' => 0,
                        'release_date' => date('Y-m-d'),
                        'deduction' => ($deduction),/*($amount*.05)*/
                        'net_amount' => ($netamount),/*($amount*.95)*/
                        'planid' => 2,
                        'b_status' => 3,
                        'plan_status' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    $transactionInfo = \App\TransactionInfo::create([
                        'txnid' => $insTxn,
                        'payment_addr' => $addr,
                        'payee_addr' => 'Club Reward',
                        'transaction_hash' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);

                    $amt = $amount;

                    $entry = $userDetail->clubIncome()->where('remaining_usdt', '>', 0)->where('status', 0);
                    $reduceSalary = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
                    \Log::info('reduce Club ' . $reduceSalary);
                    foreach ($entry as $club) {
                        if ($amt > 0) {
                            $updLifetime = \App\ClubIncome::where('id', $club->id)->update([
                                'remaining_usdt' => (($amt > $club->remaining_usdt) ? 0 : ($club->remaining_usdt - $amt)),
                                ($club->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn,
                                'status' => 1
                            ]);
                            $amt -= $club->remaining_usdt;
                        } else {
                            break;
                        }
                        \Log::info('remaining after club ' . $amt);
                    }
                    if ($amt > 0) {
                        \DB::rollback();
                        \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is income less than withdraw.');
                        return redirect()->back()->with('warning', 'You have some issue with withdraw. Please contact to admin.');
                    } else {
                        $withdrawInfoInsert = \App\WithdrawInfo::create([
                            'txnid' => $insTxn,
                            'club' => $reduceSalary,
                        ]);
                    }

                    \DB::commit();
                    return redirect('/User/ClubReward')->with('success', 'Your withdrawal request has been processed. It will be credited to your wallet within 24 hours.');
                } catch (Exception $e) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                    return redirect()->back()->with('warning', 'You have some issue with withdraw. Please Try again.');
                }

            } else {
                return redirect('/User/EditProfile')->with('warning', 'Please update wallet address first.');
            }
        } else {
            return redirect('/User/ClubReward')->with('warning', 'You do not have any amount to withdraw.');
        }


    }





}
