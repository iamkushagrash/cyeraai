<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class CpsIncomeController extends Controller
{
    public function cpsGeneration()
    {
        \Log::info("==================================================================");
        \Log::info(">>> [CPS GENERATION START] Starting Daily ROI Calculation at " . now());
        \Log::info("==================================================================");

        $todayDate = date('Y-m-d');
        $allDeposits = \App\StackingDeposite::where('status', 1)
            ->where('staketype', '>', 0)
            ->where('planid', '>', 0)
            ->get();

        \Log::info("[CPS SCAN] Found " . $allDeposits->count() . " active Staking Deposits in database.");

        $profileStore = \App\ProfileStore::where('id', 1)->first() ?: (object)['price' => 1];
        $processedCount = 0;
        $skippedCount = 0;

        foreach ($allDeposits as $deposit) {
            $depositId = $deposit->id;
            $userId = $deposit->userid;
            $depositCreatedAt = substr($deposit->created_at, 0, 10);

            // 1. Check Date (T+1 Cycle)
            if ($depositCreatedAt >= $todayDate) {
                \Log::info("[ROI SKIPPED] Deposit #{$depositId} (User ID {$userId}): Staked TODAY ({$deposit->created_at}). Daily ROI starts from next day (T+1 cycle).");
                $skippedCount++;
                continue;
            }

            // 2. Check duplicate ROI already credited today
            $alreadyCreditedToday = \App\CpsIncome::where('txnid', $depositId)
                ->where('created_at', '>=', $todayDate . ' 00:00:00')
                ->where('created_at', '<=', $todayDate . ' 23:59:59')
                ->exists();

            if ($alreadyCreditedToday) {
                \Log::info("[ROI SKIPPED] Deposit #{$depositId} (User ID {$userId}): Daily ROI already credited today ({$todayDate}).");
                $skippedCount++;
                continue;
            }

            // 3. User Details & Permission
            $userDetail = $deposit->userDetail();
            if (!$userDetail) {
                \Log::warning("[ROI SKIPPED] Deposit #{$depositId}: UserDetail record not found for user_details.id = {$userId}.");
                $skippedCount++;
                continue;
            }

            $user = $userDetail->user();
            if (!$user || $user->permission != 1) {
                $perm = $user ? $user->permission : 'null';
                \Log::warning("[ROI SKIPPED] Deposit #{$depositId} (User ID {$userId}): User permission is inactive (permission: {$perm}).");
                $skippedCount++;
                continue;
            }

            // 4. Check ROI Status
            if ($userDetail->roi_status == 0) {
                \Log::info("[ROI SKIPPED] Deposit #{$depositId} (User {$user->uuid}): User's ROI status is disabled (roi_status = 0).");
                $skippedCount++;
                continue;
            }

            // 5. Check User Capping Limit
            if ($userDetail->capping == 1) {
                \Log::info("[ROI SKIPPED] Deposit #{$depositId} (User {$user->uuid}): User has reached max capping limit (capping = 1).");
                $skippedCount++;
                continue;
            }

            // 6. Check Total Remaining Capping
            $userActiveDeposits = \App\StackingDeposite::where([['userid', $userId], ['status', '>', 0]])->get();
            $totalCap = 0;
            foreach ($userActiveDeposits as $item) {
                try {
                    $totalCap += (float) Crypt::decrypt($item->capamount);
                } catch (\Exception $e) {}
            }

            if ($totalCap <= 0) {
                \Log::info("[ROI SKIPPED] Deposit #{$depositId} (User {$user->uuid}): Total remaining capping is $0.00.");
                $skippedCount++;
                continue;
            }

            // 7. Check Loan Status
            $loanStatus = (((!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->status == 0 && $userDetail->userLoanStatus()->remaining == 0) || is_null($userDetail->userLoanStatus())) ? 0 : 3);
            if ($loanStatus == 3) {
                \Log::info("[ROI SKIPPED] Deposit #{$depositId} (User {$user->uuid}): User has an active/pending loan repayment.");
                $skippedCount++;
                continue;
            }

            // 8. Calculate Daily ROI Rate based on Booster status
            $dailyRoiRate = 0.50; // Base: 0.50% daily
            $boosterName = 'Base (0.5%)';
            if ($userDetail->booster == 3) {
                $dailyRoiRate = 1.50; // Booster 2: 1.50% daily
                $boosterName = 'Booster 2 (1.5%)';
            } elseif ($userDetail->booster == 2) {
                $dailyRoiRate = 1.00; // Booster 1: 1.00% daily
                $boosterName = 'Booster 1 (1.0%)';
            }

            $cpsUsdt = ($deposit->usdt * $dailyRoiRate) / 100;
            if ($cpsUsdt <= 0) {
                \Log::warning("[ROI SKIPPED] Deposit #{$depositId}: Calculated CPS amount is $0.");
                $skippedCount++;
                continue;
            }

            // 9. Generate Daily ROI Income Record (Clean 1:1 Base in Database)
            $insIncomeEntry = \App\CpsIncome::create([
                'userid'         => $deposit->userid,
                'txnid'          => $deposit->id,
                'amount'         => $cpsUsdt,
                'remaining'      => $cpsUsdt,
                'amt_usdt'       => $cpsUsdt,
                'remaining_usdt' => $cpsUsdt,
                'status'         => 0,
                'created_at'     => now(),
            ]);

            \Log::info("[ROI SUCCESS] Generated \${$cpsUsdt} USDT ({$boosterName}) on \${$deposit->usdt} stake for User {$user->uuid} (Deposit #{$depositId}). Income ID: {$insIncomeEntry->id}");
            $processedCount++;
        }

        \Log::info("==================================================================");
        \Log::info(">>> [CPS GENERATION COMPLETE] Processed: {$processedCount} | Skipped: {$skippedCount}");
        \Log::info("==================================================================");
    }


    public function ProductCpsGeneration()
    {
        $getAllDeposit = \App\StackingDeposite::where([['status', 1], ['staketype', 0], ['planid', '>', 2], ['created_at', '<', date('Y-m-d')]])->get();
        foreach ($getAllDeposit as $deposit) {
            $loanStatus = (((!is_null($deposit->userDetail()->userLoanStatus()) && $deposit->userDetail()->userLoanStatus()->status == 0 && $deposit->userDetail()->userLoanStatus()->remaining == 0) || is_null($deposit->userDetail()->userLoanStatus())) ? 0 : 3);
            if (
                $loanStatus != 3 &&
                !is_null($deposit->walletTransfer()) &&
                $deposit->usdt == $deposit->walletTransfer()->amount &&
                $deposit->userDetail()->user()->permission == 1 &&
                $deposit->userDetail()->capping != 1 &&
                $deposit->userDetail()->roi_status != 0
            ) {
                $cps = $deposit->usdt * $deposit->planDetail()->cps / 100;
                //\Log::info('for planid '.$deposit->id.' cps BC '.$cps);
                $cappingFunction = new StackingDetailController();
                $returnAmount = $cappingFunction->cappingCalculation($deposit->userid, $cps);
                //\Log::info('Return Amount AC '.$returnAmount);
                $insIncomeEntry = \App\CpsIncome::create([
                    'userid' => $deposit->userid,
                    'txnid' => $deposit->id,
                    'amount' => $returnAmount,
                    'remaining' => $returnAmount,
                    'amt_usdt' => $returnAmount,
                    'remaining_usdt' => $returnAmount,
                    'status' => 0,
                    'created_at' => now(),
                ]);
            }
        }
    }


    //NowPayments
    public function showPage()
    {
        $userExistingPayment = \App\TransactionDetail::where([['userid', \Session::get('user.id')], ['paymentstatus', '<', 2], ['txntype', 0], ['release_date', '>', date('Y-m-d H:i:s', strtotime('- 5 minutes', strtotime(now())))]])->join('transaction_infos', 'transaction_details.id', '=', 'transaction_infos.txnid')
            ->select('comments as payment_id', 'amountusdt as amount', 'transaction_infos.payment_addr as pay_address')->first();
        return view('user.depositnowpayment')->with('payment', $userExistingPayment);
    }

    public function submitTransaction(Request $request)
    {
        \Log::info('Amount ' . $request->amount);
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {//dd($validator->errors(),$request->plan);
            return redirect('/User/Deposit')->with('errors', $validator->errors());
        }
        if ($request->amount < 24) {
            return redirect('/User/Deposit')->with('warning', 'amount should be greater than 25');
        }

        $arrayParm = array(
            "price_amount" => $request->amount,
            "price_currency" => "usd",
            "pay_currency" => "usdtbsc",
            "ipn_callback_url" => "https://nowpayments.io",
            "is_fixed_rate" => true,
            "is_fee_paid_by_user" => false,
        );
        $npObject = new NPController();

        $paymentCreation = json_decode($npObject->createPayment($arrayParm));

        if (isset($paymentCreation->payment_status)) {
            $insertTransactionDetails = \App\TransactionDetail::insertGetId([
                'userid' => \Session::get('user.id'),
                'txntype' => 0,
                'amountsftc' => 0,
                'amountusdt' => $request->amount,
                'remaining' => $request->amount,
                'paymentstatus' => 0,
                'txndesc' => 'User Deposit',
                'comments' => $paymentCreation->payment_id,
                'currency' => $paymentCreation->pay_currency,
                'release_date' => date('Y-m-d H:i:s', strtotime('+ 19 minutes', strtotime(now()))),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $insertTransactionInfo = \App\TransactionInfo::create([
                'txnid' => $insertTransactionDetails,
                'payment_addr' => $paymentCreation->pay_address,
                'payee_addr' => '',
                'transaction_hash' => '',
                'amount' => 0,
                'contract_addr' => $paymentCreation->purchase_id,
                'txn_status' => 0,
            ]);

            \Log::info(json_encode($paymentCreation));
            $transactionDetail = \App\TransactionDetail::where('transaction_details.id', $insertTransactionDetails)
                ->join('transaction_infos', 'transaction_details.id', '=', 'transaction_infos.txnid')
                ->select('comments as payment_id', 'amountusdt as amount', 'transaction_infos.payment_addr as pay_address')->first();
            return view('user.depositnowpayment')->with('payment', ($transactionDetail));
        } else {
            $userExistingPayment = \App\TransactionDetail::where([['userid', \Session::get('user.id')], ['paymentstatus', '<', 2]])->join('transaction_infos', 'transaction_details.id', '=', 'transaction_infos.txnid')
                ->select('comments as payment_id', 'amountusdt as amount', 'transaction_infos.payment_addr as pay_address')->first();
            return view('user.depositnowpayment')->with('payment', $userExistingPayment)->with('warning', 'Something went wrong. Please try again after some time.');
        }
        /*{ ▼
          +"payment_id": "5097213251"
          +"payment_status": "waiting"
          +"pay_address": "0x609579991050aF114D5127493C8C45C42BBa261d"
          +"price_amount": 100
          +"price_currency": "usd"
          +"pay_amount": 102.001234
          +"amount_received": 99.997366
          +"pay_currency": "usdterc20"
          +"order_id": null
          +"order_description": null
          +"payin_extra_id": null
          +"ipn_callback_url": "https://nowpayments.io"
          +"customer_email": null
          +"created_at": "2025-08-25T18:33:17.474Z"
          +"updated_at": "2025-08-25T18:33:17.474Z"
          +"purchase_id": "5867222481"
          +"smart_contract": null
          +"network": "eth"
          +"network_precision": null
          +"time_limit": null
          +"burning_percent": null
          +"expiration_estimate_date": "2025-08-25T18:53:17.474Z"
          +"is_fixed_rate": true
          +"is_fee_paid_by_user": true
          +"valid_until": "2025-09-01T18:33:17.474Z"
          +"type": "crypto2crypto"
          +"product": "api"
          +"origin_ip": "2409:40d4:205f:9d89:2d46:4b9b:3084:819"
        }*/
        /*$paymentCreation='{"payment_id":"5977953151","payment_status":"waiting","pay_address":"0x24070CE7202c541381F3dF0aeb58d253006b9262","price_amount":13,"price_currency":"usd","pay_amount":13.25552622,"amount_received":12.96277893,"pay_currency":"usdtbsc","order_id":null,"order_description":null,"payin_extra_id":null,"ipn_callback_url":"https://nowpayments.io","customer_email":null,"created_at":"2025-08-27T16:56:58.628Z","updated_at":"2025-08-27T16:56:58.628Z","purchase_id":"4740818607","smart_contract":null,"network":"bsc","network_precision":null,"time_limit":null,"burning_percent":null,"expiration_estimate_date":"2025-08-27T17:16:58.628Z","is_fixed_rate":true,"is_fee_paid_by_user":false,"valid_until":"2025-09-03T16:56:58.628Z","type":"crypto2crypto","product":"api","origin_ip":"157.48.244.247"}';*///dd(($paymentCreation));    
    }

    public function paymentStatus($id)
    {
        $npObject = new NPController();
        $paymentCreation = json_decode($npObject->getPaymentStatus($id));
        $paymentStatus = 0; // 0=unpaid, 1=paid, 2=failed/returned
        $ransactionUrl = '';
        $transactionMessage = '';

        // -------------------------
        // Fetch transaction (only non-failed)
        // -------------------------
        $transaction = \App\TransactionDetail::where([
            ['comments', $id],
            ['txntype', 0],
            ['paymentstatus', '<', 3] // 0,1,2 allowed
        ])->first();

        // -------------------------
        // Early return ONLY if no transaction found
        // -------------------------
        if (!$transaction) {
            return response()->json([
                'status' => 1,
                'payment_status' => $paymentCreation->payment_status,
                'paid' => $paymentCreation->actually_paid,
                'totalAmount' => $paymentCreation->pay_amount,
                'transaction_status' => 1,
                'url' => '/User/Deposit',
                'transaction_message' => 'Transaction not found or already failed.'
            ]);
        }

        // -------------------------
        // DB Transaction with Lock
        // -------------------------
        DB::transaction(function () use (&$transaction, $paymentCreation, &$paymentStatus, &$ransactionUrl, &$transactionMessage) {
            // Lock row to prevent double-processing
            $transaction = \App\TransactionDetail::where('id', $transaction->id)
                ->lockForUpdate()
                ->first();

            // -------------------------
            // Already confirmed
            // -------------------------
            if ($transaction->paymentstatus == 2) {
                $paymentStatus = 1;
                $ransactionUrl = '/User/Deposit';
                $transactionMessage = 'Transaction already confirmed.';
                return;
            }

            // -------------------------
            // Failed / Refunded / Expired
            // -------------------------
            if (in_array($paymentCreation->payment_status, ['failed', 'refunded', 'expired'])) {
                \App\TransactionDetail::where('id', $transaction->id)
                    ->update([
                        'paymentstatus' => 3,
                        'updated_at' => now()
                    ]);
                \App\TransactionInfo::where('txnid', $transaction->id)
                    ->update([
                        'txn_status' => 3,
                        'updated_at' => now()
                    ]);
                $paymentStatus = 2;
                $ransactionUrl = '/User/Deposit';
                $transactionMessage = 'Your previous transaction either failed or refunded. Please initiate a new transaction.';
                return;
            }

            // -------------------------
            // Confirmed / Paid / Partially paid
            // -------------------------
            if (
                in_array($paymentCreation->payment_status, ['finished', 'confirmed', 'sending']) ||
                ($paymentCreation->payment_status == 'partially_paid' ||
                    ($transaction->release_date <= date('Y-m-d H:i:s') && $transaction->remaining <= $paymentCreation->actually_paid))
            ) {
                // Update transaction details
                \App\TransactionDetail::where('id', $transaction->id)
                    ->update([
                        'paymentstatus' => 2,
                        'remaining' => 0,
                        'amountusdt' => $paymentCreation->actually_paid,
                        'updated_at' => now()
                    ]);

                \App\TransactionInfo::where('txnid', $transaction->id)
                    ->update([
                        'txn_status' => 2,
                        'amount' => $paymentCreation->actually_paid,
                        'transaction_hash' => $paymentCreation->payin_hash,
                        'updated_at' => now()
                    ]);

                // Update user wallet
                \App\WalletTransfer::create([
                    'userid' => $transaction->userid,
                    'txnid' => $transaction->id,
                    'fromWallet' => 'deposite',
                    'toWallet' => 'wallet',
                    'amount' => $paymentCreation->actually_paid,
                    'fromUser' => $transaction->userid,
                    'release_date' => date('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update promotional / deposit amount
                $promotionalAdd = \App\AccountDeposit::firstOrNew(['userid' => $transaction->userid]);
                $amt = !is_null($promotionalAdd->amount) ? Crypt::decrypt($promotionalAdd->amount) + $paymentCreation->actually_paid : $paymentCreation->actually_paid;
                $promotionalAdd->amount = Crypt::encrypt($amt);
                $promotionalAdd->save();

                // Send confirmation email
                try {
                    $sendMail = new SupportQueryController();
                    $userdt = \App\UserDetails::where('id', $transaction->userid)->first();
                    $details['email'] = $userdt->user()->email;
                    $details['subject'] = 'Your deposit confirmed at Cyera AI';
                    $details['view'] = 'depositmail';
                    $details['amount'] = $paymentCreation->actually_paid;
                    $details['userid'] = $userdt->user()->uuid;
                    $sendMail->sendMailgun($details);
                } catch (\Exception $e) {
                    \Log::info('Error sending deposit mail for userid ' . $transaction->userid);
                    \Log::info($e->getMessage());
                }

                $paymentStatus = 1;
                $ransactionUrl = '/User/Deposit';
                $transactionMessage = 'Transaction confirmed successfully. Amount transferred to your wallet.';
                return;
            }
        });

        // -------------------------
        // Return JSON for frontend
        // -------------------------
        return response()->json([
            'status' => 1,
            'payment_status' => $paymentCreation->payment_status,
            'paid' => $paymentCreation->actually_paid,
            'totalAmount' => $paymentCreation->pay_amount,
            'transaction_status' => $paymentStatus,
            'url' => $ransactionUrl,
            'transaction_message' => $transactionMessage
        ]);
    }


    public function paymentStatusOld($id)
    {
        $npObject = new NPController();

        $paymentCreation = json_decode($npObject->getPaymentStatus($id));
        $paymentStatus = 0;//0=unpaid 1=paid 2=filed or returned
        $ransactionUrl = '';
        $transactionMessage = '';
        $transaction = \App\TransactionDetail::where([['paymentstatus', '<', 2], ['comments', $id], ['txntype', 0]])->first();
        if (!is_null($transaction)) {
            if ($paymentCreation->payment_status == 'failed' || $paymentCreation->payment_status == 'refunded' || $paymentCreation->payment_status == 'expired') {
                $transactionDetailUpate = \App\TransactionDetail::where('id', $transaction->id)->update([
                    'paymentstatus' => 3,
                    'updated_at' => now(),
                ]);
                $transactionInfoUpdate = \App\TransactionInfo::where('txnid', $transaction->id)->update([
                    'txn_status' => 3,
                    'updated_at' => now(),
                ]);
                $paymentStatus = 2;
                $ransactionUrl = '/User/Deposit';
                $transactionMessage = 'Your previous transaction either failed or refunded due to some reasons. Please initiate new transaction.';
                //return redirect('/User/Deposit')->with('warning','Your previous transaction either failed or refunded due to some reasons. Please initiate new transaction.');
            } elseif ($paymentCreation->payment_status == 'finished' || $paymentCreation->payment_status == 'confirmed' || $paymentCreation->payment_status == 'sending' || ($paymentCreation->payment_status == 'partially_paid' || $transaction->release_date <= date('Y-m-d H:i:s') && $transaction->remaining <= $paymentCreation->actually_paid)) {
                $transactionDetailUpate = \App\TransactionDetail::where('id', $transaction->id)->update([
                    'paymentstatus' => 2,
                    'remaining' => 0,
                    'amountusdt' => $paymentCreation->actually_paid,
                    'updated_at' => now(),
                ]);
                $transactionInfoUpdate = \App\TransactionInfo::where('txnid', $transaction->id)->update([
                    'txn_status' => 2,
                    'amount' => $paymentCreation->actually_paid,
                    'transaction_hash' => $paymentCreation->payin_hash,
                    'updated_at' => now(),
                ]);
                $userWalletUpdate = \App\WalletTransfer::create([
                    'userid' => $transaction->userid,
                    'txnid' => $transaction->id,
                    'fromWallet' => 'deposite',
                    'toWallet' => 'wallet',
                    'amount' => $paymentCreation->actually_paid,
                    'fromUser' => $transaction->userid,
                    'release_date' => date('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $promotionalAdd = \App\AccountDeposit::firstOrNew([
                    'userid' => $transaction->userid,
                ]);
                if (!is_null($promotionalAdd->amount)) {
                    $amt = Crypt::decrypt($promotionalAdd->amount) + ($paymentCreation->actually_paid);
                } else {
                    $amt = ($paymentCreation->actually_paid);
                }
                $promotionalAdd->amount = (Crypt::encrypt($amt));
                $promotionalAdd->save();
                $sendMail = new SupportQueryController();
                $userdt = \App\UserDetails::where('id', $transaction->userid)->first();
                try {
                    $details['email'] = $userdt->user()->email;
                    $details['subject'] = 'Your deposit confirmed at Cyera AI';
                    $details['view'] = 'depositmail';
                    $details['amount'] = $paymentCreation->actually_paid;
                    $details['userid'] = $userdt->user()->uuid;
                    $status = $sendMail->sendMailgun($details);
                } catch (Exception $e) {
                    \Log::info('Error in sending Topupmail for userid ' . $transaction->userid);
                    \Log::info($e->messages());
                }
                $paymentStatus = 1;
                $ransactionUrl = '/User/Deposit';
                $transactionMessage = 'Transaction confirmed successfully. Amount transferred to your wallet.';
            }
        }
        \Log::info('payment status of id ' . $id);
        \Log::info(json_encode($paymentCreation));
        \Log::info(json_encode(array('status' => 1, 'payment_status' => $paymentCreation->payment_status, 'paid' => $paymentCreation->actually_paid, 'totalAmount' => $paymentCreation->pay_amount)));
        return json_encode(array('status' => 1, 'payment_status' => $paymentCreation->payment_status, 'paid' => $paymentCreation->actually_paid, 'totalAmount' => $paymentCreation->pay_amount, 'transaction_status' => $paymentStatus, 'url' => $ransactionUrl, 'transaction_message' => $transactionMessage));

    }


}
