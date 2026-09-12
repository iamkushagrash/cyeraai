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
use App\Helpers\Eip712Helper;

class WithdrawInfoController extends Controller
{
    // ============================================================
    // 1. WORKING INCOMES WITHDRAWAL (DIRECT, LEVEL, POOL, RANK)
    // ============================================================

    public function withdrawWorkingPage()
    {
        $detail = \App\ProfileStore::where('id', 1)->first();
        $user = \App\UserDetails::where('id', \Session::get('user.id'))->first();
        $remainingTxn = \App\TransactionDetail::where([
            ['userid', \Session::get('user.id')],
            ['txntype', 1],
            ['paymentstatus', '<', 2],
            ['planid', 0],
            ['comments', 'working']
        ])->first();

        $caiPrice = $detail ? (float) $detail->price : 1.0;
        if ($caiPrice <= 0) $caiPrice = 1.0;

        $poolRemaining = (float)\App\PoolIncome::where('userid', $user->id)->where('status', 0)->sum('remaining_usdt') + (float)$user->clubIncome()->where('status', 0)->sum('remaining_usdt');
        $rankRemaining = (float)\App\RankIncome::where('userid', $user->id)->where('status', 0)->sum('remaining_usdt') + (float)$user->lifetimeIncome()->where('status', 0)->sum('remaining');
        $levelRemaining = (float)$user->levelIncome()->where('status', 0)->sum('remaining_usdt');
        $bonusRemaining = (float)$user->bonusReward()->where('status', '!=', 3)->sum('remaining_usdt');

        $workingRemainingUsdt = $levelRemaining + $bonusRemaining + $poolRemaining + $rankRemaining;

        return view('user.withdrawrequest')
            ->with('detail', $detail)
            ->with('user', $user)
            ->with('remaining', $remainingTxn)
            ->with('workingRemainingUsdt', $workingRemainingUsdt)
            ->with('caiPrice', $caiPrice);
    }

    // Alias for backward compatibility
    public function withdrawPage()
    {
        return $this->withdrawWorkingPage();
    }

    public function withdrawWorkingRequest(Request $request)
    {
        set_time_limit(0);

        $profile = \App\ProfileStore::where('id', 1)->first();
        if ($profile->usdtbep20_withdrawal_status == 0) {
            return redirect()->back()->with('warning', 'Withdrawal method is not available, please try again later');
        }

        $valid = Validator::make($request->all(), [
            'currency'   => ['required', 'string'],
            'amountusdt' => ['required', 'numeric'],
        ])->validate();

        if (is_null($request->honeypotu) || $request->honeypotu == '') {
            return redirect()->back()->with('warning', 'There is some issue. Please try again.');
        }

        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

        if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
            return redirect('/User/WithdrawRequest')->with('warning', 'Conversion not permitted. Please contact Admin');
        }
        // Working Balance Check (Level + Direct Bonus + Global Pool + Rank Income)
        $poolRemaining = (float)\App\PoolIncome::where('userid', $userDetail->id)->where('status', 0)->sum('remaining_usdt') + (float)$userDetail->clubIncome()->where('status', 0)->sum('remaining_usdt');
        $rankRemaining = (float)\App\RankIncome::where('userid', $userDetail->id)->where('status', 0)->sum('remaining_usdt') + (float)$userDetail->lifetimeIncome()->where('status', 0)->sum('remaining');
        $levelRemaining = (float)$userDetail->levelIncome()->where('status', 0)->sum('remaining_usdt');
        $bonusRemaining = (float)$userDetail->bonusReward()->where('status', '!=', 3)->sum('remaining_usdt');

        $workingBalance = $levelRemaining + $bonusRemaining + $poolRemaining + $rankRemaining;

        if ($request->amountusdt <= 0) {
            return redirect()->back()->with('warning', 'Please enter a valid withdrawal amount.');
        }

        if ($workingBalance < $request->amountusdt) {
            return redirect()->to('/User/WithdrawRequest')->with('warning', 'Insufficient Working Income balance. Available: $' . number_format($workingBalance, 2));
        }

        if (!is_null($userDetail->assetDetail())) {
            \DB::beginTransaction();
            try {
                if ($request->currency == 'usdt') {
                    $addr = $userDetail->assetDetail()->usdttrc20addr;
                } elseif ($request->currency == 'usdtbep20') {
                    $addr = $userDetail->assetDetail()->usdtbep20addr;
                } elseif ($request->currency == 'bank') {
                    $addr = $userDetail->assetDetail()->accountno;
                } else {
                    $addr = $userDetail->assetDetail()->usdtbep20addr ?? ($userDetail->assetDetail()->usdttrc20addr ?? '');
                }

                if (empty($addr)) {
                    return redirect('/User/EditProfile')->with('warning', 'Please update your wallet address in profile first.');
                }

                $txnId = $this->insertWithdrawEntry(\Session::get('user.id'), 0, $request->amountusdt, $addr, $request->currency, '', 'working');
                
                $amt = (float) $request->amountusdt;
                $reducePool = 0;
                $reduceLevel = 0;
                $reduceReward = 0;
                $reduceRank = 0;

                // (A) Level Income
                $entry = \App\LevelIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', '!=', 3)->orderBy('id', 'asc')->get();
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

                // (B) Direct Bonus
                if ($amt > 0) {
                    $entry = \App\BonusReward::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', '!=', 3)->orderBy('id', 'asc')->get();
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

                // (C) Global Pool Income (pool_incomes & club_incomes) -> goes to withdraw_infos.club
                if ($amt > 0) {
                    $entryPool = \App\PoolIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();
                    foreach ($entryPool as $pInc) {
                        if ($amt > 0) {
                            $deductUsdt = min($amt, (float) $pInc->remaining_usdt);
                            \App\PoolIncome::where('id', $pInc->id)->update([
                                'remaining_usdt' => max(0, $pInc->remaining_usdt - $deductUsdt),
                                'remaining'      => max(0, (float)$pInc->remaining - $deductUsdt),
                                'status'         => ($pInc->remaining_usdt - $deductUsdt <= 0.000001) ? 1 : 0
                            ]);
                            $reducePool += $deductUsdt;
                            $amt -= $deductUsdt;
                        } else {
                            break;
                        }
                    }

                    if ($amt > 0) {
                        $entryClub = \App\ClubIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', '!=', 3)->orderBy('id', 'asc')->get();
                        foreach ($entryClub as $club) {
                            if ($amt > 0) {
                                $deductUsdt = min($amt, (float) $club->remaining_usdt);
                                \App\ClubIncome::where('id', $club->id)->update([
                                    'remaining_usdt' => max(0, $club->remaining_usdt - $deductUsdt),
                                    ($club->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                ]);
                                $reducePool += $deductUsdt;
                                $amt -= $deductUsdt;
                            } else {
                                break;
                            }
                        }
                    }
                }

                // (D) Rank Leadership Income (rank_incomes & achievement_incomes) -> goes to withdraw_infos.salary
                if ($amt > 0) {
                    $entryRank = \App\RankIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();
                    foreach ($entryRank as $rInc) {
                        if ($amt > 0) {
                            $deductUsdt = min($amt, (float) $rInc->remaining_usdt);
                            \App\RankIncome::where('id', $rInc->id)->update([
                                'remaining_usdt' => max(0, $rInc->remaining_usdt - $deductUsdt),
                                'remaining'      => max(0, (float)$rInc->remaining - $deductUsdt),
                                'status'         => ($rInc->remaining_usdt - $deductUsdt <= 0.000001) ? 1 : 0
                            ]);
                            $reduceRank += $deductUsdt;
                            $amt -= $deductUsdt;
                        } else {
                            break;
                        }
                    }

                    if ($amt > 0) {
                        $entryAch = \App\AchievementIncome::where('userid', $userDetail->id)->where('remaining', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();
                        foreach ($entryAch as $ach) {
                            if ($amt > 0) {
                                $deductUsdt = min($amt, (float) $ach->remaining);
                                \App\AchievementIncome::where('id', $ach->id)->update([
                                    'remaining' => max(0, $ach->remaining - $deductUsdt),
                                    ($ach->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                                ]);
                                $reduceRank += $deductUsdt;
                                $amt -= $deductUsdt;
                            } else {
                                break;
                            }
                        }
                    }
                }

                \App\WithdrawInfo::create([
                    'txnid'    => $txnId,
                    'stacking' => 0,
                    'level'    => $reduceLevel,
                    'bonus'    => $reduceReward,
                    'club'     => $reducePool,
                    'salary'   => $reduceRank,
                ]);

                \App\TransactionDetail::where('id', $txnId)->update([
                    'b_status'   => 3,
                    'planid'     => 2,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                \DB::commit();
                return redirect('/User/WithdrawRequest')->with('success', 'Working Income withdrawal request of $' . number_format($request->amountusdt, 2) . ' submitted successfully!');
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                return redirect('/User/WithdrawRequest')->with('warning', 'You have some issue with withdraw. Please Try again.');
            }
        } else {
            return redirect('/User/EditProfile')->with('warning', 'Please update wallet address first.');
        }
    }

    // Alias for backward compatibility
    public function withdrawRequest(Request $request)
    {
        return $this->withdrawWorkingRequest($request);
    }

    /**
     * EIP-712 Signature for Instant On-Chain Working Income Withdrawal from USDTWithdrawalVault
     */
    public function requestWorkingWithdrawSignature(Request $request)
    {
        $request->validate([
            'amountusdt'     => 'required|numeric|min:0.01',
            'wallet_address' => 'nullable|string',
        ]);

        $userId = \Session::get('user.id');
        $userDetail = \App\UserDetails::where('id', $userId)->first();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized / Session Expired.'], 401);
        }

        $grossAmount = (float) $request->amountusdt;
        if ($grossAmount <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Please enter a valid withdrawal amount.'], 400);
        }

        // Available Working Balance Check
        $workingBalance = (float) (
            $userDetail->levelIncome()->where('status', 0)->sum('remaining_usdt') +
            $userDetail->bonusReward()->where('status', '!=', 3)->sum('remaining_usdt') +
            $userDetail->clubIncome()->where('status', 0)->sum('remaining_usdt') +
            $userDetail->lifetimeIncome()->where('status', 0)->sum('remaining')
        );

        if ($workingBalance < $grossAmount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient Working Income balance. Available: $' . number_format($workingBalance, 2)
            ], 400);
        }

        // Determine destination recipient wallet
        $recipient = $request->wallet_address;
        if (empty($recipient) || !preg_match('/^0x[a-fA-F0-9]{40}$/', $recipient)) {
            $recipient = \Session::get('user.walletaddress') ?? ($userDetail->assetDetail()->usdtbep20addr ?? '');
            if (empty($recipient) || !preg_match('/^0x[a-fA-F0-9]{40}$/', $recipient)) {
                $user = $userDetail->user();
                if ($user && preg_match('/^0x[a-fA-F0-9]{40}$/', $user->email)) {
                    $recipient = $user->email;
                } elseif ($user && preg_match('/^0x[a-fA-F0-9]{40}$/', $user->uuid)) {
                    $recipient = $user->uuid;
                }
            }
        }

        if (empty($recipient) || !preg_match('/^0x[a-fA-F0-9]{40}$/', $recipient)) {
            return response()->json(['status' => 'error', 'message' => 'Please connect a valid Web3 BNB Chain wallet address.'], 400);
        }

        // 10% Admin Fee Calculation
        $adminFee = round($grossAmount * 0.10, 4);
        $netPayout = round($grossAmount - $adminFee, 4);

        if ($netPayout <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Net payout amount must be greater than zero.'], 400);
        }

        // 18 decimals for BSC-USDT
        $amountWei = bcmul((string) $netPayout, '1000000000000000000', 0);
        $withdrawalId = (string) (time() . rand(100, 999));
        $expiry = (string) (time() + 600);

        $vaultAddress = env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x0D1Cf84DcB6Ad9dF2d2f7a5998C441569e87684b');
        $chainId = "56"; // BSC Mainnet
        $privateKey = env('SIGNER_PRIVATE_KEY', '38765232db1a84b6571252884f7168205f3207242cd31f99ab4e76ad76f5a67b');

        // Generate Pure PHP EIP-712 cryptographic signature (No shell_exec needed)
        try {
            $signResult = Eip712Helper::signWithdrawal(
                $recipient,
                $amountWei,
                $withdrawalId,
                $expiry,
                $vaultAddress,
                $chainId,
                $privateKey
            );
            $signature = $signResult['signature'];
        } catch (\Exception $e) {
            \Log::error("Working Withdrawal Signature Generation Failed: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to generate cryptographic authorization signature: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'recipient'     => $recipient,
                'gross_amount'  => $grossAmount,
                'admin_fee'     => $adminFee,
                'net_amount'    => $netPayout,
                'amount_wei'    => $amountWei,
                'withdrawal_id' => $withdrawalId,
                'expiry'        => $expiry,
                'signature'     => $signature,
                'vault_address' => $vaultAddress,
            ]
        ]);
    }

    /**
     * Confirm On-Chain Working Income Withdrawal & Save Tx Hash
     */
    public function confirmWorkingWithdrawal(Request $request)
    {
        $request->validate([
            'tx_hash'       => 'required|string',
            'withdrawal_id' => 'required',
            'gross_amount'  => 'required|numeric|min:0.01',
            'net_amount'    => 'required|numeric|min:0.01',
            'recipient'     => 'required|string',
        ]);

        $userId = \Session::get('user.id');
        $userDetail = \App\UserDetails::where('id', $userId)->first();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized / Session Expired.'], 401);
        }

        $txHash = strtolower(trim($request->tx_hash));
        $grossAmount = (float) $request->gross_amount;
        $netAmount = (float) $request->net_amount;
        $adminFee = (float) ($grossAmount - $netAmount);
        $recipient = $request->recipient;

        // Prevent duplicate transaction log
        $alreadyLogged = \App\TransactionInfo::where('transaction_hash', $txHash)->exists();
        if ($alreadyLogged) {
            return response()->json(['status' => 'success', 'message' => 'Withdrawal already processed.']);
        }

        \DB::beginTransaction();
        try {
            $amt = $grossAmount;
            $reducePool = 0;
            $reduceLevel = 0;
            $reduceReward = 0;

            // 1. Insert Transaction Detail (Instant Approved / Confirmed)
            $insTxn = \App\TransactionDetail::insertGetId([
                'userid'        => $userId,
                'txntype'       => 1,
                'amountsftc'    => 0,
                'amountusdt'    => $grossAmount,
                'remaining'     => 0,
                'paymentstatus' => 2, // Confirmed
                'txndesc'       => 'Working Income Instant Web3 Withdrawal',
                'currency'      => 'usdtbep20',
                'comments'      => 'working',
                'paidby'        => 0,
                'release_date'  => date('Y-m-d'),
                'deduction'     => $adminFee,
                'net_amount'    => $netAmount,
                'planid'        => 2,
                'plan_status'   => 0,
                'b_status'      => 3, // Auto-Approved
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);

            // 2. Insert Transaction Info with Tx Hash
            \App\TransactionInfo::create([
                'txnid'            => $insTxn,
                'payment_addr'     => $recipient,
                'payee_addr'       => env('USDT_WITHDRAWAL_VAULT_ADDRESS', '0x0D1Cf84DcB6Ad9dF2d2f7a5998C441569e87684b'),
                'transaction_hash' => $txHash,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);

            // 3. (A) Level Income FIFO
            $entry = \App\LevelIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', '!=', 3)->orderBy('id', 'asc')->get();
            $reduceLevel = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
            foreach ($entry as $level) {
                if ($amt > 0) {
                    $deductUsdt = min($amt, (float) $level->remaining_usdt);
                    \App\LevelIncome::where('id', $level->id)->update([
                        'remaining_usdt' => max(0, $level->remaining_usdt - $deductUsdt),
                        ($level->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn
                    ]);
                    $amt -= $deductUsdt;
                } else {
                    break;
                }
            }

            // 4. (B) Direct Bonus FIFO
            if ($amt > 0) {
                $entry = \App\BonusReward::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', '!=', 3)->orderBy('id', 'asc')->get();
                $reduceReward = ($amt > $entry->sum('remaining_usdt')) ? $entry->sum('remaining_usdt') : $amt;
                foreach ($entry as $bonus) {
                    if ($amt > 0) {
                        $deductUsdt = min($amt, (float) $bonus->remaining_usdt);
                        \App\BonusReward::where('id', $bonus->id)->update([
                            'remaining_usdt' => max(0, $bonus->remaining_usdt - $deductUsdt),
                            ($bonus->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn
                        ]);
                        $amt -= $deductUsdt;
                    } else {
                        break;
                    }
                }
            }

            $reduceRank = 0;

            // 5. (C) Global Pool Income FIFO (pool_incomes & club_incomes) -> goes to withdraw_infos.club
            if ($amt > 0) {
                $entryPool = \App\PoolIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();
                foreach ($entryPool as $pInc) {
                    if ($amt > 0) {
                        $deductUsdt = min($amt, (float) $pInc->remaining_usdt);
                        \App\PoolIncome::where('id', $pInc->id)->update([
                            'remaining_usdt' => max(0, $pInc->remaining_usdt - $deductUsdt),
                            'remaining'      => max(0, (float)$pInc->remaining - $deductUsdt),
                            'status'         => ($pInc->remaining_usdt - $deductUsdt <= 0.000001) ? 1 : 0
                        ]);
                        $reducePool += $deductUsdt;
                        $amt -= $deductUsdt;
                    } else {
                        break;
                    }
                }

                if ($amt > 0) {
                    $entryClub = \App\ClubIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', '!=', 3)->orderBy('id', 'asc')->get();
                    foreach ($entryClub as $club) {
                        if ($amt > 0) {
                            $deductUsdt = min($amt, (float) $club->remaining_usdt);
                            \App\ClubIncome::where('id', $club->id)->update([
                                'remaining_usdt' => max(0, $club->remaining_usdt - $deductUsdt),
                                ($club->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn
                            ]);
                            $reducePool += $deductUsdt;
                            $amt -= $deductUsdt;
                        } else {
                            break;
                        }
                    }
                }
            }

            // 6. (D) Rank Leadership Income FIFO (rank_incomes & achievement_incomes) -> goes to withdraw_infos.salary
            if ($amt > 0) {
                $entryRank = \App\RankIncome::where('userid', $userDetail->id)->where('remaining_usdt', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();
                foreach ($entryRank as $rInc) {
                    if ($amt > 0) {
                        $deductUsdt = min($amt, (float) $rInc->remaining_usdt);
                        \App\RankIncome::where('id', $rInc->id)->update([
                            'remaining_usdt' => max(0, $rInc->remaining_usdt - $deductUsdt),
                            'remaining'      => max(0, (float)$rInc->remaining - $deductUsdt),
                            'status'         => ($rInc->remaining_usdt - $deductUsdt <= 0.000001) ? 1 : 0
                        ]);
                        $reduceRank += $deductUsdt;
                        $amt -= $deductUsdt;
                    } else {
                        break;
                    }
                }

                if ($amt > 0) {
                    $entryAch = \App\AchievementIncome::where('userid', $userDetail->id)->where('remaining', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();
                    foreach ($entryAch as $ach) {
                        if ($amt > 0) {
                            $deductUsdt = min($amt, (float) $ach->remaining);
                            \App\AchievementIncome::where('id', $ach->id)->update([
                                'remaining' => max(0, $ach->remaining - $deductUsdt),
                                ($ach->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn
                            ]);
                            $reduceRank += $deductUsdt;
                            $amt -= $deductUsdt;
                        } else {
                            break;
                        }
                    }
                }
            }

            // 7. Insert WithdrawInfo summary
            \App\WithdrawInfo::create([
                'txnid'    => $insTxn,
                'stacking' => 0,
                'level'    => $reduceLevel,
                'bonus'    => $reduceReward,
                'club'     => $reducePool,
                'salary'   => $reduceRank,
            ]);

            \DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "Instant Web3 Withdrawal of \${$netAmount} USDT completed successfully on BNB Smart Chain!",
                'data'    => [
                    'tx_hash'      => $txHash,
                    'gross_amount' => $grossAmount,
                    'admin_fee'    => $adminFee,
                    'net_amount'   => $netAmount,
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("ConfirmWorkingWithdrawal Exception: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to log withdrawal: ' . $e->getMessage()], 500);
        }
    }

    public function withdrawWorkingHistory()
    {
        $withhistory = DB::table('transaction_details')
            ->where([
                ['transaction_details.userid', Session::get('user.id')],
                ['transaction_details.txntype', '1'],
            ])
            ->where(function($q) {
                $q->where('transaction_details.comments', 'working')
                  ->orWhere(function($sub) {
                      $sub->where('transaction_details.comments', '!=', 'roi')
                          ->where('transaction_details.txndesc', 'not like', '%ROI%');
                  });
            })
            ->join('transaction_infos', 'transaction_details.id', '=', 'transaction_infos.txnid')
            ->join('user_details', 'transaction_details.userid', '=', 'user_details.id')
            ->join('users', 'user_details.userid', '=', 'users.id')
            ->select('transaction_details.amountsftc', 'transaction_details.amountusdt', 'transaction_details.deduction', 'transaction_details.net_amount', 'transaction_details.currency', 'users.uuid', 'users.email', 'users.usersname', 'transaction_infos.transaction_hash')
            ->selectRaw('DATE_FORMAT(transaction_details.created_at,"%d-%m-%Y %H:%i") as created_at')
            ->selectRaw('case when transaction_details.paymentstatus=0 then "Verification Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Cancelled" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Expired" end as status')
            ->selectRaw('case when transaction_details.paymentstatus=0 then "badge-warning" when transaction_details.paymentstatus=1 then "badge-warning" when transaction_details.paymentstatus=2 then "badge-success" when transaction_details.paymentstatus=3 then "badge-danger" when transaction_details.paymentstatus=4 then "badge-danger" when transaction_details.paymentstatus=5 then "badge-danger" end as statusclass')
            ->orderByRaw('transaction_details.id DESC')
            ->get();

        return view('user.withdrawhistory')->with('history', $withhistory);
    }


    // ============================================================
    // 2. STAKING ROI (CPS / CAI YIELD) WITHDRAWAL
    // ============================================================

    public function withdrawRoiPage()
    {
        $detail = \App\ProfileStore::where('id', 1)->first();
        $user = \App\UserDetails::where('id', \Session::get('user.id'))->first();
        $remainingTxn = \App\TransactionDetail::where([
            ['userid', \Session::get('user.id')],
            ['txntype', 1],
            ['paymentstatus', '<', 2],
            ['planid', 0],
            ['comments', 'roi']
        ])->first();

        $caiPrice = $detail ? (float) $detail->price : 1.0;
        if ($caiPrice <= 0) $caiPrice = 1.0;

        // 1. Total remaining CAI tokens accumulated from daily ROI
        $roiRemainingCai = (float) \App\CpsIncome::where('userid', $user->id)->where('status', 0)->sum('remaining');
        $roiUsdDynamic = $roiRemainingCai * $caiPrice;

        // 2. Total active capping limit remaining in staking deposits
        $activeDeposits = \App\StackingDeposite::where([['userid', $user->id], ['status', '>', 0]])->get();
        $totalRemainingCapping = 0;
        foreach ($activeDeposits as $deposit) {
            try {
                $totalRemainingCapping += (float) Crypt::decrypt($deposit->capamount);
            } catch (\Exception $e) {}
        }

        // 3. Capping in CAI terms & Max Claimable CAI
        $cappingInCai = ($caiPrice > 0) ? ($totalRemainingCapping / $caiPrice) : 0;
        $maxClaimableCai = min($roiRemainingCai, $cappingInCai);
        $maxClaimableUsdt = $maxClaimableCai * $caiPrice;

        return view('user.withdrawroirequest')
            ->with('detail', $detail)
            ->with('user', $user)
            ->with('remaining', $remainingTxn)
            ->with('roiRemainingCai', $roiRemainingCai)
            ->with('roiUsdDynamic', $roiUsdDynamic)
            ->with('totalRemainingCapping', $totalRemainingCapping)
            ->with('cappingInCai', $cappingInCai)
            ->with('maxClaimableCai', $maxClaimableCai)
            ->with('maxClaimableUsdt', $maxClaimableUsdt)
            ->with('caiPrice', $caiPrice);
    }

    public function withdrawRoiRequest(Request $request)
    {
        set_time_limit(0);

        $profile = \App\ProfileStore::where('id', 1)->first();
        if ($profile->usdtbep20_withdrawal_status == 0) {
            return redirect()->back()->with('warning', 'Withdrawal method is not available, please try again later');
        }

        $caiPrice = $profile ? (float) $profile->price : 1.0;
        if ($caiPrice <= 0) $caiPrice = 1.0;

        $valid = Validator::make($request->all(), [
            'currency'   => ['required', 'string'],
            'amountcai'  => ['nullable', 'numeric'],
            'amountusdt' => ['nullable', 'numeric'],
        ])->validate();

        if (is_null($request->honeypotu) || $request->honeypotu == '') {
            return redirect()->back()->with('warning', 'There is some issue. Please try again.');
        }

        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

        if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
            return redirect('/User/RoiWithdrawRequest')->with('warning', 'Conversion not permitted. Please contact Admin');
        }

        // Input is CAI tokens
        $requestedCai = 0.0;
        if ($request->has('amountcai') && (float)$request->amountcai > 0) {
            $requestedCai = (float) $request->amountcai;
        } elseif ($request->has('amountusdt') && (float)$request->amountusdt > 0) {
            $requestedCai = ($caiPrice > 0) ? ((float)$request->amountusdt / $caiPrice) : (float)$request->amountusdt;
        }

        if ($requestedCai <= 0) {
            return redirect()->back()->with('warning', 'Please enter a valid CAI amount to claim.');
        }

        $requestedUsdt = $requestedCai * $caiPrice;

        // 1. Total remaining CAI tokens
        $roiRemainingCai = (float) \App\CpsIncome::where('userid', $userDetail->id)->where('status', 0)->sum('remaining');

        if ($requestedCai > ($roiRemainingCai + 0.000001)) {
            return redirect()->to('/User/RoiWithdrawRequest')->with('warning', 'Insufficient Staking ROI balance. You have ' . number_format($roiRemainingCai, 4) . ' CAI.');
        }

        // 2. Active Staking Capping Check (in USDT)
        $activeDeposits = \App\StackingDeposite::where([['userid', $userDetail->id], ['status', '>', 0]])->get();
        $totalRemainingCapping = 0;
        foreach ($activeDeposits as $deposit) {
            try {
                $totalRemainingCapping += (float) Crypt::decrypt($deposit->capamount);
            } catch (\Exception $e) {}
        }

        if ($requestedUsdt > ($totalRemainingCapping + 0.01)) {
            $cappingInCai = ($caiPrice > 0) ? ($totalRemainingCapping / $caiPrice) : 0;
            return redirect()->to('/User/RoiWithdrawRequest')->with('warning', 'Requested amount (' . number_format($requestedCai, 4) . ' CAI ≈ $' . number_format($requestedUsdt, 2) . ') exceeds your active deposit capping limit (' . number_format($cappingInCai, 4) . ' CAI ≈ $' . number_format($totalRemainingCapping, 2) . '). Please retopup/upgrade stake to unlock more capping.');
        }

        if (!is_null($userDetail->assetDetail())) {
            \DB::beginTransaction();
            try {
                if ($request->currency == 'usdt') {
                    $addr = $userDetail->assetDetail()->usdttrc20addr;
                } elseif ($request->currency == 'usdtbep20') {
                    $addr = $userDetail->assetDetail()->usdtbep20addr;
                } else {
                    $addr = $userDetail->assetDetail()->usdtbep20addr ?? ($userDetail->assetDetail()->usdttrc20addr ?? '');
                }

                if (empty($addr)) {
                    return redirect('/User/EditProfile')->with('warning', 'Please update your wallet address in profile first.');
                }

                $txnId = $this->insertWithdrawEntry(\Session::get('user.id'), $requestedCai, $requestedUsdt, $addr, $request->currency, '', 'roi');

                // 1. Deduct Capping from active staking deposits in USDT
                $stackingCtrl = new \App\Http\Controllers\StackingDetailController();
                $stackingCtrl->cappingCalculation($userDetail->id, $requestedUsdt);

                // 2. Deduct CAI tokens from cps_incomes.remaining in FIFO order
                $caiPending = $requestedCai;
                $entries = \App\CpsIncome::where('userid', $userDetail->id)->where('remaining', '>', 0)->where('status', 0)->orderBy('id', 'asc')->get();

                foreach ($entries as $cps) {
                    if ($caiPending > 0) {
                        $deductCai = min($caiPending, (float) $cps->remaining);
                        $newRemaining = max(0, $cps->remaining - $deductCai);
                        
                        \App\CpsIncome::where('id', $cps->id)->update([
                            'remaining' => $newRemaining,
                            'status'    => ($newRemaining <= 0.000001) ? 1 : 0,
                            ($cps->intxna == 0) ? 'intxna' : 'intxnb' => $txnId
                        ]);
                        $caiPending -= $deductCai;
                    } else {
                        break;
                    }
                }

                \App\WithdrawInfo::create([
                    'txnid'    => $txnId,
                    'stacking' => $requestedUsdt,
                    'level'    => 0,
                    'bonus'    => 0,
                    'club'     => 0,
                ]);

                \App\TransactionDetail::where('id', $txnId)->update([
                    'b_status'   => 3,
                    'planid'     => 2,
                    'amountsftc' => $requestedCai,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                \DB::commit();
                return redirect('/User/RoiWithdrawRequest')->with('success', 'Staking ROI claim of ' . number_format($requestedCai, 4) . ' CAI (≈ $' . number_format($requestedUsdt, 2) . ' USDT) submitted successfully!');
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                return redirect('/User/RoiWithdrawRequest')->with('warning', 'You have some issue with withdraw. Please Try again.');
            }
        } else {
            return redirect('/User/EditProfile')->with('warning', 'Please update wallet address first.');
        }
    }

    public function withdrawRoiHistory()
    {
        $withhistory = DB::table('transaction_details')
            ->where([
                ['transaction_details.userid', Session::get('user.id')],
                ['transaction_details.txntype', '1'],
            ])
            ->where(function($q) {
                $q->where('transaction_details.comments', 'roi')
                  ->orWhere('transaction_details.txndesc', 'like', '%ROI%');
            })
            ->join('transaction_infos', 'transaction_details.id', '=', 'transaction_infos.txnid')
            ->join('user_details', 'transaction_details.userid', '=', 'user_details.id')
            ->join('users', 'user_details.userid', '=', 'users.id')
            ->select('transaction_details.amountsftc', 'transaction_details.amountusdt', 'transaction_details.deduction', 'transaction_details.net_amount', 'transaction_details.currency', 'users.uuid', 'users.email', 'users.usersname', 'transaction_infos.transaction_hash')
            ->selectRaw('DATE_FORMAT(transaction_details.created_at,"%d-%m-%Y %H:%i") as created_at')
            ->selectRaw('case when transaction_details.paymentstatus=0 then "Verification Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Cancelled" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Expired" end as status')
            ->selectRaw('case when transaction_details.paymentstatus=0 then "badge-warning" when transaction_details.paymentstatus=1 then "badge-warning" when transaction_details.paymentstatus=2 then "badge-success" when transaction_details.paymentstatus=3 then "badge-danger" when transaction_details.paymentstatus=4 then "badge-danger" when transaction_details.paymentstatus=5 then "badge-danger" end as statusclass')
            ->orderByRaw('transaction_details.id DESC')
            ->get();

        return view('user.withdrawroihistory')->with('history', $withhistory);
    }

    public function insertWithdrawEntry($userId, $styamount, $amount, $addr, $paymode, $randId, $withdrawType = 'working')
    {
        $insTxn = \App\TransactionDetail::insertGetId([
            'userid'        => $userId,
            'txntype'       => 1,
            'amountsftc'    => ($styamount),
            'amountusdt'    => ($amount),
            'remaining'     => ($amount),
            'paymentstatus' => 1,
            'txndesc'       => ($withdrawType == 'roi' ? 'Staking ROI Withdrawal' : 'Working Income Withdrawal'),
            'currency'      => $paymode,
            'comments'      => $withdrawType,
            'paidby'        => 0,
            'release_date'  => date('Y-m-d'),
            'deduction'     => ($amount * .10),
            'net_amount'    => ($amount * .90),
            'planid'        => 0,
            'plan_status'   => 0,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        $transactionInfo = \App\TransactionInfo::create([
            'txnid'            => $insTxn,
            'payment_addr'     => $addr,
            'payee_addr'       => '',
            'transaction_hash' => $randId,
            'created_at'       => date('Y-m-d H:i:s'),
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
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        \Log::info($response);

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
        $valid = Validator::make($request->all(), [
            'currency' => ['required', 'string'],
        ])->validate();

        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

        if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
            return redirect('/User/LifetimeAchievementReward')->with('warning', 'Conversion not permitted. Please contact Admin');
        }
        if (sizeof($userDetail->lifetimeIncome()) && ($userDetail->lifetimeIncome()->where('remaining', '>', 0)->sum('remaining') > 0)) {
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
                    'deduction' => ($deduction),
                    'net_amount' => ($netamount),
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
                        \App\AchievementIncome::where('id', $lifetime->id)->update([
                            'remaining' => (($amt > $lifetime->remaining) ? 0 : ($lifetime->remaining - $amt)),
                            ($lifetime->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn,
                            'status' => 1
                        ]);
                        $amt -= $lifetime->remaining;
                    } else {
                        break;
                    }
                }
                if ($amt > 0) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is income less than withdraw.');
                    return redirect()->back()->with('warning', 'You have some issue with withdraw. Please contact to admin.');
                } else {
                    \App\WithdrawInfo::create([
                        'txnid' => $insTxn,
                        'salary' => $reduceSalary,
                    ]);
                }

                \DB::commit();
                return redirect('/User/LifetimeAchievementReward')->with('success', 'Your withdrawal request has been processed. It will be credited to your wallet within 24 hours.');
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                return redirect()->back()->with('warning', 'You have some issue with withdraw. Please Try again.');
            }
        } else {
            return redirect('/User/LifetimeAchievementReward')->with('warning', 'You do not have any amount to withdraw.');
        }
    }

    public function withdrawClubIncome(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'currency' => ['required', 'string'],
        ])->validate();

        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();

        if (!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status == 0) {
            return redirect('/User/ClubReward')->with('warning', 'Conversion not permitted. Please contact Admin');
        }
        if (sizeof($userDetail->clubIncome()) && ($userDetail->clubIncome()->where('remaining_usdt', '>', 0)->sum('remaining_usdt') > 0)) {
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
                    'deduction' => ($deduction),
                    'net_amount' => ($netamount),
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
                        \App\ClubIncome::where('id', $club->id)->update([
                            'remaining_usdt' => (($amt > $club->remaining_usdt) ? 0 : ($club->remaining_usdt - $amt)),
                            ($club->intxna == 0) ? 'intxna' : 'intxnb' => $insTxn,
                            'status' => 1
                        ]);
                        $amt -= $club->remaining_usdt;
                    } else {
                        break;
                    }
                }
                if ($amt > 0) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is income less than withdraw.');
                    return redirect()->back()->with('warning', 'You have some issue with withdraw. Please contact to admin.');
                } else {
                    \App\WithdrawInfo::create([
                        'txnid' => $insTxn,
                        'club' => $reduceSalary,
                    ]);
                }

                \DB::commit();
                return redirect('/User/ClubReward')->with('success', 'Your withdrawal request has been processed. It will be credited to your wallet within 24 hours.');
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                return redirect()->back()->with('warning', 'You have some issue with withdraw. Please Try again.');
            }
        } else {
            return redirect('/User/ClubReward')->with('warning', 'You do not have any amount to withdraw.');
        }
    }
}
