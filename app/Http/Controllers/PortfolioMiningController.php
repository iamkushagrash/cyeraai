<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use App\UserDetails;
use App\CpsIncome;
use App\StackingDeposite;
use App\CaiMiningLedger;
use App\ProfileStore;
use App\Helpers\Eip712Helper;

class PortfolioMiningController extends Controller
{
    /**
     * Show CAI Mining & DEX Portfolio Liquidation Page
     */
    public function miningPage()
    {
        $userDetail = $this->getAuthUserDetail();
        if (!$userDetail) {
            return redirect('/login');
        }

        $userId = $userDetail->id;
        $livePrice = $this->getLiveCaiPrice();

        // 1. Unmined USDT ROI
        $unminedUsdt = (float) CpsIncome::where('userid', $userId)->where('status', 0)->sum('amt_usdt');
        $estimatedCaiToMine = $livePrice > 0 ? round($unminedUsdt / $livePrice, 6) : 0;

        // 2. User Protocol CAI Holding Balance
        $holdingCai = (float) ($userDetail->cai_balance ?? 0);
        $holdingValueUsdt = round($holdingCai * $livePrice, 4);

        // 3. Dynamic Runtime Capping
        $remainingCapping = $this->calculateRuntimeCapping($userId);

        // 4. Max Sellable vs Excess Held
        $maxSellableUsdt = min($holdingValueUsdt, $remainingCapping);
        $maxSellableCai = $livePrice > 0 ? min($holdingCai, round($maxSellableUsdt / $livePrice, 6)) : 0;
        $excessHeldCai = max(0, round($holdingCai - $maxSellableCai, 6));

        // 5. Complete Audit Ledger History
        $ledgers = CaiMiningLedger::where('userid', $userId)
            ->orderBy('id', 'desc')
            ->paginate(15);

        $contracts = [
            'miningEngine' => env('CYERA_MINING_ENGINE_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217'),
            'caiToken'     => env('CAI_TOKEN_ADDRESS', '0x4756618F389A46819008Aff01ad0f91A38154eDB'),
            'usdtToken'    => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'pancakePair'  => env('PANCAKESWAP_PAIR_ADDRESS', '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c'),
            'chainId'      => 56,
        ];

        return view('user.mining', compact(
            'userDetail',
            'livePrice',
            'unminedUsdt',
            'estimatedCaiToMine',
            'holdingCai',
            'holdingValueUsdt',
            'remainingCapping',
            'maxSellableUsdt',
            'maxSellableCai',
            'excessHeldCai',
            'ledgers',
            'contracts'
        ));
    }

    /**
     * Get Current Authenticated User Detail Model
     */
    private function getAuthUserDetail()
    {
        $userId = Session::get('user.id') ?? Session::get('user.userid');
        
        if (!$userId && \Auth::check()) {
            $userId = \Auth::id();
        }

        if (!$userId && Session::has('user.uuid')) {
            $u = \App\User::where('uuid', Session::get('user.uuid'))->first();
            if ($u) $userId = $u->id;
        }

        if (!$userId) {
            return null;
        }

        // 1. Direct search by id or userid in UserDetails
        $ud = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if ($ud) {
            return $ud;
        }

        // 2. Search via Users table
        $user = \App\User::where('id', $userId)->orWhere('uuid', $userId)->first();
        if ($user) {
            return UserDetails::where('userid', $user->id)->orWhere('id', $user->id)->first();
        }

        return null;
    }

    /**
     * Calculate Runtime Dynamic Remaining Capping from Stacking Deposits
     */
    private function calculateRuntimeCapping($userId)
    {
        $deposits = StackingDeposite::where('userid', $userId)
            ->where('status', '>', 0)
            ->get();

        $totalCapping = 0.0;
        foreach ($deposits as $deposit) {
            try {
                $cap = (float) Crypt::decrypt($deposit->capamount);
                if ($cap > 0) {
                    $totalCapping += $cap;
                }
            } catch (\Exception $e) {
                // If not encrypted or fallback
                if (is_numeric($deposit->capamount) && $deposit->capamount > 0) {
                    $totalCapping += (float) $deposit->capamount;
                }
            }
        }

        return round($totalCapping, 4);
    }

    /**
     * Deduct Amount from Stacking Deposits Capping in FIFO Order
     */
    private function deductRuntimeCapping($userId, $amountToDeduct)
    {
        $deposits = StackingDeposite::where('userid', $userId)
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        $remainingToDeduct = (float) $amountToDeduct;

        foreach ($deposits as $deposit) {
            if ($remainingToDeduct <= 0) break;

            try {
                $currentCap = (float) Crypt::decrypt($deposit->capamount);
            } catch (\Exception $e) {
                $currentCap = (float) $deposit->capamount;
            }

            if ($currentCap <= 0) {
                $deposit->status = 2; // Capped/Finished
                $deposit->save();
                continue;
            }

            if ($currentCap <= $remainingToDeduct) {
                $remainingToDeduct -= $currentCap;
                $deposit->capamount = Crypt::encrypt(0);
                $deposit->status = 2; // Completed
                $deposit->save();
            } else {
                $newCap = $currentCap - $remainingToDeduct;
                $remainingToDeduct = 0;
                $deposit->capamount = Crypt::encrypt($newCap);
                $deposit->save();
            }
        }

        // Check if all deposits capped
        $newRuntimeCap = $this->calculateRuntimeCapping($userId);
        if ($newRuntimeCap <= 0) {
            UserDetails::where('id', $userId)->update(['capping' => 1]);
        }

        return $newRuntimeCap;
    }

    /**
     * Fetch Live DEX Price for CAI/USDT
     */
    public function getLiveCaiPrice()
    {
        try {
            // Check PriceUpdateController or ProfileStore
            $profile = ProfileStore::where('id', 1)->first();
            $dbPrice = $profile && $profile->price > 0 ? (float) $profile->price : 1.00;

            // Attempt live on-chain check via PancakeSwap Pair if possible
            $pairAddress = env('PANCAKESWAP_PAIR_ADDRESS', '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c');
            $dexUrl = "https://api.dexscreener.com/latest/dex/pairs/bsc/" . $pairAddress;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $dexUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Cyera-Mining-Engine/1.0');
            $resp = curl_exec($ch);
            curl_close($ch);

            if ($resp) {
                $json = json_decode($resp, true);
                if (isset($json['pairs'][0]['priceUsd']) && is_numeric($json['pairs'][0]['priceUsd']) && $json['pairs'][0]['priceUsd'] > 0) {
                    return round((float) $json['pairs'][0]['priceUsd'], 6);
                }
            }

            return round($dbPrice, 6);
        } catch (\Exception $e) {
            Log::warning("Live CAI Price Fetch Exception: " . $e->getMessage());
            return 1.00;
        }
    }

    /**
     * API: Get Comprehensive Mining & Liquidation Stats for User Dashboard
     */
    public function getMiningStats(Request $request)
    {
        $userDetail = $this->getAuthUserDetail();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized / Session Expired.'], 401);
        }

        $userId = $userDetail->id;
        $livePrice = $this->getLiveCaiPrice();

        // 1. Unmined USDT ROI (Sum of cps_incomes with status = 0)
        $unminedUsdt = (float) CpsIncome::where('userid', $userId)->where('status', 0)->sum('amt_usdt');
        $unminedRowsCount = CpsIncome::where('userid', $userId)->where('status', 0)->count();

        // 2. Estimated CAI tokens to be mined
        $estimatedCaiToMine = $livePrice > 0 ? round($unminedUsdt / $livePrice, 6) : 0;

        // 3. User's System CAI Holding Balance
        $holdingCai = (float) ($userDetail->cai_balance ?? 0);
        $holdingValueUsdt = round($holdingCai * $livePrice, 4);

        // 4. Runtime Dynamic Remaining Capping
        $remainingCapping = $this->calculateRuntimeCapping($userId);

        // 5. Capping Guard Calculation on Holding Balance
        $maxSellableUsdt = min($holdingValueUsdt, $remainingCapping);
        $maxSellableCai = $livePrice > 0 ? min($holdingCai, round($maxSellableUsdt / $livePrice, 6)) : 0;
        $excessHeldCai = max(0, round($holdingCai - $maxSellableCai, 6));
        $excessHeldUsdt = round($excessHeldCai * $livePrice, 4);

        // 6. Recent Mining / Selling History
        $history = CaiMiningLedger::where('userid', $userId)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        // 7. Contracts configuration
        $contracts = [
            'miningEngine' => env('CYERA_MINING_ENGINE_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217'),
            'caiToken'     => env('CAI_TOKEN_ADDRESS', '0x4756618F389A46819008Aff01ad0f91A38154eDB'),
            'usdtToken'    => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'pancakePair'  => env('PANCAKESWAP_PAIR_ADDRESS', '0x4B33d9a80AEAe68aD6d9EE84FD816DB9A29D6A2c'),
            'chainId'      => 56,
        ];

        return response()->json([
            'status' => 'success',
            'data'   => [
                'unmined_usdt'             => $unminedUsdt,
                'unmined_count'            => $unminedRowsCount,
                'live_cai_price'           => $livePrice,
                'estimated_cai_to_mine'    => $estimatedCaiToMine,
                'holding_cai_balance'      => $holdingCai,
                'holding_value_usdt'       => $holdingValueUsdt,
                'remaining_capping'        => $remainingCapping,
                'max_sellable_usdt'        => $maxSellableUsdt,
                'max_sellable_cai'         => $maxSellableCai,
                'excess_held_cai'          => $excessHeldCai,
                'excess_held_usdt'         => $excessHeldUsdt,
                'history'                  => $history,
                'contracts'                => $contracts,
            ]
        ]);
    }

    /**
     * Action 1: Mine Accumulated USDT ROI into Protocol CAI Holdings (Zero Capping Impact)
     */
    public function mineRoiToCai(Request $request)
    {
        $userDetail = $this->getAuthUserDetail();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized / Session Expired.'], 401);
        }

        $userId = $userDetail->id;

        DB::beginTransaction();
        try {
            // Lock unmined records
            $unminedRows = CpsIncome::where('userid', $userId)
                ->where('status', 0)
                ->lockForUpdate()
                ->get();

            $totalUsdt = (float) $unminedRows->sum('amt_usdt');
            if ($totalUsdt <= 0 || $unminedRows->isEmpty()) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'No unmined ROI available to mine.'], 400);
            }

            $livePrice = $this->getLiveCaiPrice();
            if ($livePrice <= 0) $livePrice = 1.00;

            $caiMined = round($totalUsdt / $livePrice, 6);

            // 1. Mark unmined cps_incomes as mined (status = 1, remaining_usdt = 0)
            CpsIncome::where('userid', $userId)
                ->where('status', 0)
                ->update([
                    'status'         => 1,
                    'remaining_usdt' => 0,
                    'remaining'      => 0,
                    'amount'         => DB::raw("`amt_usdt` / {$livePrice}"),
                    'updated_at'     => now(),
                ]);

            // 2. Credit Mined CAI to user_details (Tokens stay inside protocol)
            $userDetail = UserDetails::where('id', $userId)->lockForUpdate()->first();
            $newCaiBalance = round(((float) $userDetail->cai_balance) + $caiMined, 6);
            $userDetail->cai_balance = $newCaiBalance;
            $userDetail->save();

            // 3. Runtime Capping Remains Unchanged
            $runtimeCapping = $this->calculateRuntimeCapping($userId);

            // 4. Audit Log in cai_mining_ledgers
            CaiMiningLedger::create([
                'userid'           => $userId,
                'type'             => 'mine',
                'usdt_amount'      => $totalUsdt,
                'cai_amount'       => $caiMined,
                'cai_price'        => $livePrice,
                'capping_before'   => $runtimeCapping,
                'capping_deducted' => 0.00, // NO CAPPING DEDUCTION AT MINING
                'capping_after'    => $runtimeCapping,
                'tx_hash'          => null,
                'status'           => 1,
                'notes'            => "Mined \${$totalUsdt} USDT ROI into {$caiMined} CAI @ \${$livePrice} / CAI",
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "Successfully mined {$caiMined} CAI tokens from \${$totalUsdt} USDT ROI!",
                'data'    => [
                    'mined_cai'       => $caiMined,
                    'usdt_converted'  => $totalUsdt,
                    'cai_price'       => $livePrice,
                    'new_cai_balance' => $newCaiBalance,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("MineRoiToCai Exception: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to mine ROI: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Action 2: Request EIP-712 Signature for sellPortfolio() on DEX (Smart Capping Guard Enforced)
     */
    public function requestSellPortfolioSignature(Request $request)
    {
        $request->validate([
            'cai_amount' => 'required|numeric|min:0.0001',
        ]);

        $userDetail = $this->getAuthUserDetail();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized / Session Expired.'], 401);
        }

        $userId = $userDetail->id;
        $requestedCai = (float) $request->cai_amount;

        $user = $userDetail->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User profile not found.'], 404);
        }

        // Determine destination wallet address (prefer currently connected Web3 wallet)
        $walletAddress = $request->wallet_address ?: Session::get('user.walletaddress');
        if (empty($walletAddress) || !preg_match('/^0x[a-fA-F0-9]{40}$/', $walletAddress)) {
            if (preg_match('/^0x[a-fA-F0-9]{40}$/', $user->email)) {
                $walletAddress = $user->email;
            } elseif (preg_match('/^0x[a-fA-F0-9]{40}$/', $user->uuid)) {
                $walletAddress = $user->uuid;
            } else {
                return response()->json(['status' => 'error', 'message' => 'Please connect a valid Web3 BNB Chain wallet to sell.'], 400);
            }
        }

        // 1. Verify User has sufficient CAI Holdings
        $currentCaiBalance = (float) ($userDetail->cai_balance ?? 0);
        if ($currentCaiBalance < $requestedCai) {
            return response()->json([
                'status' => 'error',
                'message' => "Insufficient CAI balance. You have {$currentCaiBalance} CAI."
            ], 400);
        }

        // 2. Verify Runtime Dynamic Capping Guard
        $runtimeCapping = $this->calculateRuntimeCapping($userId);
        if ($runtimeCapping <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your Capping Limit is $0.00. Please Re-topup / Upgrade your Staking Package to unlock and sell your CAI tokens.'
            ], 400);
        }

        $livePrice = $this->getLiveCaiPrice();
        $requestedUsdtValue = $requestedCai * $livePrice;

        // Smart Capping Guard: Cap sell strictly to remaining capping limit
        $grossUsdtValue = min($requestedUsdtValue, $runtimeCapping);
        $grossCaiAmount = round($grossUsdtValue / $livePrice, 6);

        if ($grossCaiAmount <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Allowed sell amount is 0.'], 400);
        }

        // 10% Admin Deduction
        $adminFeeRate = 0.10;
        $adminFeeUsdt = round($grossUsdtValue * $adminFeeRate, 4);
        $adminFeeCai = round($grossCaiAmount * $adminFeeRate, 6);
        
        $netUsdtValue = round($grossUsdtValue - $adminFeeUsdt, 4);
        $netCaiToSell = round($grossCaiAmount - $adminFeeCai, 6);

        if ($netCaiToSell <= 0 || $netUsdtValue <= 0) {
            return response()->json(['status' => 'error', 'message' => 'Net claim amount after 10% admin deduction is 0.'], 400);
        }

        // Slippage Protection: 2% slippage on net amount
        $minUsdtOut = round($netUsdtValue * 0.98, 6);

        // Convert to Wei (18 Decimals for CAI and BSC-USDT)
        $caiWei = bcmul((string) $netCaiToSell, '1000000000000000000', 0);
        $minUsdtWei = bcmul((string) $minUsdtOut, '1000000000000000000', 0);

        // Unique Nonce & Expiry (10 minutes)
        $nonce = (string) (time() . rand(1000, 9999));
        $expiry = (string) (time() + 600);

        $miningContract = env('CYERA_MINING_ENGINE_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217');
        $chainId = "56"; // BSC Mainnet
        $privateKey = env('SIGNER_PRIVATE_KEY', '7672820670408540bfcd0c7d34794935e4a3054455fa5c7f9cccdfdf4aca45c3');

        // Generate Pure PHP EIP-712 cryptographic signature (No shell_exec needed)
        try {
            $signResult = Eip712Helper::signSellPortfolio(
                $walletAddress,
                $caiWei,
                $minUsdtWei,
                $nonce,
                $expiry,
                $miningContract,
                $chainId,
                $privateKey
            );
            $signature = $signResult['signature'];
        } catch (\Exception $e) {
            Log::error("SellPortfolio Signature Generation Failed: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to generate cryptographic authorization signature: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'user'                => $walletAddress,
                'grossCai'            => $grossCaiAmount,
                'grossUsdt'           => $grossUsdtValue,
                'adminFeePercent'     => 10,
                'adminFeeUsdt'        => $adminFeeUsdt,
                'adminFeeCai'         => $adminFeeCai,
                'caiAmount'           => $netCaiToSell, // Net CAI swapped on DEX
                'caiAmountWei'        => $caiWei,
                'netUsdt'             => $netUsdtValue, // Net USDT received
                'minUsdtOut'          => $minUsdtOut,
                'minUsdtOutWei'       => $minUsdtWei,
                'nonce'               => $nonce,
                'expiry'              => $expiry,
                'signature'           => $signature,
                'miningContract'      => $miningContract,
                'livePrice'           => $livePrice,
                'cappingBefore'       => $runtimeCapping,
                'estimatedCappingUse' => $grossUsdtValue,
                'excessHeldCai'       => max(0, round($currentCaiBalance - $grossCaiAmount, 6)),
            ]
        ]);
    }

    /**
     * Action 3: Confirm Sold Portfolio on Blockchain -> Deduct System CAI & Deduct Capping
     */
    public function confirmPortfolioSold(Request $request)
    {
        $request->validate([
            'tx_hash'       => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{64}$/'],
            'cai_amount'    => 'required|numeric|min:0.0001', // Net CAI sold on DEX
            'gross_cai'     => 'nullable|numeric',
            'usdt_received' => 'required|numeric|min:0.0001', // Net USDT received
            'gross_usdt'    => 'nullable|numeric',
        ]);

        $userDetail = $this->getAuthUserDetail();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized / Session Expired.'], 401);
        }

        $userId = $userDetail->id;
        $txHash = strtolower(trim($request->tx_hash));
        $netCaiSold = (float) $request->cai_amount;
        $netUsdtReceived = (float) $request->usdt_received;
        
        $grossCai = $request->has('gross_cai') && (float)$request->gross_cai > 0 ? (float)$request->gross_cai : round($netCaiSold / 0.90, 6);
        $grossUsdt = $request->has('gross_usdt') && (float)$request->gross_usdt > 0 ? (float)$request->gross_usdt : round($netUsdtReceived / 0.90, 4);
        $adminFeeUsdt = round($grossUsdt - $netUsdtReceived, 4);

        // Prevent duplicate transaction execution
        $alreadyLogged = CaiMiningLedger::where('tx_hash', $txHash)->exists();
        if ($alreadyLogged) {
            return response()->json(['status' => 'success', 'message' => 'Transaction already processed.']);
        }

        DB::beginTransaction();
        try {
            $userDetail = UserDetails::where('id', $userId)->lockForUpdate()->first();
            $currentCai = (float) ($userDetail->cai_balance ?? 0);

            // 1. Deduct Full Gross CAI Tokens from System Balance (Remaining CAI stays held!)
            $newCaiBalance = max(0, round($currentCai - $grossCai, 6));
            $userDetail->cai_balance = $newCaiBalance;
            $userDetail->save();

            // 2. Deduct Gross USDT Amount from Runtime Capping Limit
            $cappingBefore = $this->calculateRuntimeCapping($userId);
            $cappingAfter = $this->deductRuntimeCapping($userId, $grossUsdt);

            // 3. Record in Audit Ledger
            $livePrice = $this->getLiveCaiPrice();
            CaiMiningLedger::create([
                'userid'           => $userId,
                'type'             => 'sell',
                'usdt_amount'      => $netUsdtReceived,
                'cai_amount'       => $grossCai,
                'cai_price'        => $livePrice,
                'capping_before'   => $cappingBefore,
                'capping_deducted' => $grossUsdt,
                'capping_after'    => $cappingAfter,
                'tx_hash'          => $txHash,
                'status'           => 1,
                'notes'            => "Claimed {$grossCai} CAI (\${$grossUsdt} USDT). 10% Admin Fee: -\${$adminFeeUsdt}. Net Swapped: {$netCaiSold} CAI -> \${$netUsdtReceived} USDT delivered to wallet.",
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "Portfolio successfully claimed! \${$netUsdtReceived} USDT (90% net after 10% admin deduction) delivered to your wallet.",
                'data'    => [
                    'new_cai_balance'  => $newCaiBalance,
                    'capping_before'   => $cappingBefore,
                    'capping_deducted' => $grossUsdt,
                    'capping_after'    => $cappingAfter,
                    'tx_hash'          => $txHash,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("ConfirmPortfolioSold Exception: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to confirm portfolio sell: ' . $e->getMessage()], 500);
        }
    }
}
