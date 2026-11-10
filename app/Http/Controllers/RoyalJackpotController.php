<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LuckyDrawDetail;
use App\LuckyDrawRound;
use App\LuckyDrawParticipant;
use App\LuckyDrawWinner;
use App\RoyalWalletLedger;
use App\UserDetails;
use App\StackingDeposite;
use App\Helpers\Eip712Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RoyalJackpotController extends Controller
{
    /**
     * Seed initial 4 Royal Jackpot Pools and their Round #1
     */
    public static function seedJackpotDetails()
    {
        $tiers = [
            [
                'pool_tier'            => 1,
                'pool_name'            => 'Royal Starter $10',
                'ticket_price'         => 10.00,
                'max_members'          => 500,
                'min_staking_required' => 100.00,
                'prizes_json'          => [
                    'rank_1'     => 500.00,
                    'rank_2'     => 300.00,
                    'rank_3'     => 200.00,
                    'rank_4_10'  => 100.00, // 7 winners * 100 = 700
                    'rank_11_25' => 50.00,  // 15 winners * 50 = 750
                    'rank_26_50' => 25.00,  // 25 winners * 25 = 625
                ],
                'total_prize_pool'     => 3075.00,
                'current_round'        => 1,
                'status'               => 1,
            ],
            [
                'pool_tier'            => 2,
                'pool_name'            => 'Royal Classic $25',
                'ticket_price'         => 25.00,
                'max_members'          => 500,
                'min_staking_required' => 100.00,
                'prizes_json'          => [
                    'rank_1'     => 1000.00,
                    'rank_2'     => 750.00,
                    'rank_3'     => 500.00,
                    'rank_4_10'  => 200.00, // 7 winners * 200 = 1400
                    'rank_11_25' => 100.00, // 15 winners * 100 = 1500
                    'rank_26_50' => 50.00,  // 25 winners * 50 = 1250
                ],
                'total_prize_pool'     => 6400.00,
                'current_round'        => 1,
                'status'               => 1,
            ],
            [
                'pool_tier'            => 3,
                'pool_name'            => 'Royal Elite $50',
                'ticket_price'         => 50.00,
                'max_members'          => 500,
                'min_staking_required' => 100.00,
                'prizes_json'          => [
                    'rank_1'     => 2000.00,
                    'rank_2'     => 1500.00,
                    'rank_3'     => 1000.00,
                    'rank_4_10'  => 500.00, // 7 winners * 500 = 3500
                    'rank_11_25' => 200.00, // 15 winners * 200 = 3000
                    'rank_26_50' => 100.00, // 25 winners * 100 = 2500
                ],
                'total_prize_pool'     => 13500.00,
                'current_round'        => 1,
                'status'               => 1,
            ],
            [
                'pool_tier'            => 4,
                'pool_name'            => 'Royal Diamond $100',
                'ticket_price'         => 100.00,
                'max_members'          => 500,
                'min_staking_required' => 100.00,
                'prizes_json'          => [
                    'rank_1'     => 5000.00,
                    'rank_2'     => 3000.00,
                    'rank_3'     => 2000.00,
                    'rank_4_10'  => 1000.00, // 7 winners * 1000 = 7000
                    'rank_11_25' => 500.00,  // 15 winners * 500 = 7500
                    'rank_26_50' => 200.00,  // 25 winners * 200 = 5000
                ],
                'total_prize_pool'     => 29500.00,
                'current_round'        => 1,
                'status'               => 1,
            ],
        ];

        foreach ($tiers as $tierData) {
            $pool = LuckyDrawDetail::firstOrCreate(
                ['pool_tier' => $tierData['pool_tier']],
                $tierData
            );

            // Ensure active round exists
            $activeRound = LuckyDrawRound::where('pool_tier', $tierData['pool_tier'])
                ->where('status', 0)
                ->first();

            if (!$activeRound) {
                LuckyDrawRound::create([
                    'pool_tier'       => $tierData['pool_tier'],
                    'round_number'    => $pool->current_round ?: 1,
                    'max_members'     => 500,
                    'current_members' => 0,
                    'ticket_price'    => $tierData['ticket_price'],
                    'total_collected' => 0.00,
                    'status'          => 0,
                    'started_at'      => now(),
                ]);
            }
        }
    }

    /**
     * Check if user meets minimum staking qualification ($100 or above)
     */
    public static function checkUserStakingEligibility($userId)
    {
        $userDetail = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if (!$userDetail) {
            return ['is_eligible' => false, 'staked_amount' => 0.00];
        }

        $activeStakingSum = (float) StackingDeposite::where('userid', $userDetail->id)
            ->where('status', 1)
            ->sum('usdt');

        $selfInvestment = (float) ($userDetail->current_self_investment ?? 0);
        $effectiveStaked = max($selfInvestment, $activeStakingSum);

        return [
            'is_eligible'   => ($effectiveStaked >= 100.00),
            'staked_amount' => $effectiveStaked,
            'user_detail'   => $userDetail,
        ];
    }

    /**
     * Main Royal Jackpot Page
     */
    public function index()
    {
        self::seedJackpotDetails();

        $userId = Session::get('user.id');
        if (!$userId && \Auth::check()) {
            $userId = \Auth::id();
        }

        $userDetail = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if ($userDetail) {
            self::reconcilePendingRoyalClaims($userDetail->id);
            $userDetail->refresh();
        }
        $eligibility = self::checkUserStakingEligibility($userId);

        $pools = LuckyDrawDetail::where('status', 1)->orderBy('pool_tier', 'asc')->get();
        foreach ($pools as $pool) {
            $activeRound = LuckyDrawRound::where('pool_tier', $pool->pool_tier)
                ->where('status', 0)
                ->first();

            if (!$activeRound) {
                $activeRound = LuckyDrawRound::create([
                    'pool_tier'       => $pool->pool_tier,
                    'round_number'    => $pool->current_round ?: 1,
                    'max_members'     => 500,
                    'current_members' => 0,
                    'ticket_price'    => $pool->ticket_price,
                    'total_collected' => 0.00,
                    'status'          => 0,
                    'started_at'      => now(),
                ]);
            }

            $pool->activeRound = $activeRound;
            $pool->slotsFilled = $activeRound->current_members;
            $pool->slotsRemaining = max(0, 500 - $activeRound->current_members);
            $pool->percentageFilled = round(($activeRound->current_members / 500) * 100, 1);

            // User's tickets in this active round
            $pool->userTicketsCount = $userDetail ? LuckyDrawParticipant::where('round_id', $activeRound->id)
                ->where('userid', $userDetail->id)
                ->count() : 0;
        }

        // User's Royal Wallet Balance & Total Won
        $royalWalletBalance = (float) ($userDetail->royal_wallet ?? 0.00);
        $totalWonAmount = $userDetail ? (float) LuckyDrawWinner::where('userid', $userDetail->id)->sum('prize_amount') : 0.00;
        $totalClaimedAmount = $userDetail ? (float) RoyalWalletLedger::where('userid', $userDetail->id)
            ->where('type', 'claim')
            ->where('status', 1)
            ->sum('amount') : 0.00;

        // User's ticket participation history
        $myEntries = $userDetail ? LuckyDrawParticipant::where('userid', $userDetail->id)
            ->with(['round'])
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'entries_page') : collect();

        // Recent Completed Rounds & Winners Hall of Fame
        $recentWinners = LuckyDrawWinner::with(['round'])
            ->orderBy('id', 'desc')
            ->take(25)
            ->get();

        $completedRounds = LuckyDrawRound::where('status', 1)
            ->orderBy('drawn_at', 'desc')
            ->take(10)
            ->get();

        // Smart Contracts Config
        $contracts = [
            'depositContract' => env('ROYAL_JACKPOT_DEPOSIT_ADDRESS') ?: '0xE4c8EF7f058051512e228dDDAC8C652fD6bc4e41',
            'vaultContract'   => env('ROYAL_JACKPOT_VAULT_ADDRESS') ?: '0x2A5DA0a78d7135A7d0Dfb4eD99430217ea8ab3ef',
            'usdtToken'       => env('USDT_TOKEN_ADDRESS') ?: '0x55d398326f99059fF775485246999027B3197955',
            'chainId'         => 56,
            'demoMode'        => (bool) env('DEMO_MODE', false),
        ];

        return view('user.royaljackpot', compact(
            'userDetail',
            'eligibility',
            'pools',
            'royalWalletBalance',
            'totalWonAmount',
            'totalClaimedAmount',
            'myEntries',
            'recentWinners',
            'completedRounds',
            'contracts'
        ));
    }

    /**
     * Enter a Royal Jackpot Pool (Buy Ticket)
     * Handles instant draw execution when 500 members are reached!
     */
    public function enterJackpot(Request $request)
    {
        $request->validate([
            'pool_tier'      => 'required|integer|in:1,2,3,4',
            'wallet_address' => 'nullable|string',
            'tx_hash'        => 'nullable|string',
        ]);

        \Log::info("Royal Jackpot enterJackpot request received:", $request->all());

        $userId = Session::get('user.id');
        if (!$userId && \Auth::check()) {
            $userId = \Auth::id();
        }

        $userDetail = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or session expired.'], 401);
        }

        // 1. Mandatory Staking Check: Must have staked $100 or above!
        $eligibility = self::checkUserStakingEligibility($userDetail->id);
        if (!$eligibility['is_eligible']) {
            return response()->json([
                'status'  => 'error',
                'message' => "Royal Jackpot is exclusively reserved for members with active staking of \$100 or above. Your current active staking is \${$eligibility['staked_amount']} USDT."
            ], 403);
        }

        $tier = (int) $request->pool_tier;
        $poolDetail = LuckyDrawDetail::where('pool_tier', $tier)->first();
        if (!$poolDetail) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Royal Jackpot pool tier.'], 400);
        }

        $ticketPrice = (float) $poolDetail->ticket_price;
        $walletAddr = strtolower(trim($request->wallet_address ?: Session::get('user.walletaddress') ?: ''));

        $isDemo = (bool) env('DEMO_MODE', false);
        $txHash = $request->tx_hash ? strtolower(trim($request->tx_hash)) : '';

        if (!$isDemo) {
            if (empty($txHash) || !preg_match('/^0x[a-fA-F0-9]{64}$/', $txHash)) {
                return response()->json(['status' => 'error', 'message' => 'Valid on-chain transaction hash is required.'], 400);
            }

            // Prevent duplicate transaction hash before doing RPC check
            if (LuckyDrawParticipant::where('tx_hash', $txHash)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'This transaction hash has already been registered.'], 400);
            }

            // Verify on-chain execution with BSC RPC nodes (strictly checks Deposit event, tier, and exact USDT amount)
            $verify = self::verifyJackpotDepositOnBsc($txHash, $walletAddr, $ticketPrice, $tier);
            if (!$verify['valid']) {
                return response()->json(['status' => 'error', 'message' => $verify['message']], 400);
            }
        } else {
            if (empty($txHash)) {
                $txHash = 'demo_' . time() . '_' . rand(1000, 9999);
            }
            if (LuckyDrawParticipant::where('tx_hash', $txHash)->exists()) {
                return response()->json(['status' => 'error', 'message' => 'This transaction hash has already been registered.'], 400);
            }
        }

        DB::beginTransaction();
        try {
            // Lock active round for this pool tier
            $round = LuckyDrawRound::where('pool_tier', $tier)
                ->where('status', 0)
                ->lockForUpdate()
                ->first();

            // If round is full or missing, create or roll to next
            if (!$round || $round->current_members >= 500) {
                if ($round && $round->current_members >= 500) {
                    self::executeInstantDraw($round);
                }
                
                $nextRoundNum = $round ? ($round->round_number + 1) : ($poolDetail->current_round ?: 1);
                $round = LuckyDrawRound::create([
                    'pool_tier'       => $tier,
                    'round_number'    => $nextRoundNum,
                    'max_members'     => 500,
                    'current_members' => 0,
                    'ticket_price'    => $ticketPrice,
                    'total_collected' => 0.00,
                    'status'          => 0,
                    'started_at'      => now(),
                ]);
            }

            $ticketNumber = $round->current_members + 1;

            // Insert participant ticket entry
            $participant = LuckyDrawParticipant::create([
                'round_id'       => $round->id,
                'pool_tier'      => $tier,
                'userid'         => $userDetail->id,
                'wallet_address' => $walletAddr,
                'ticket_number'  => $ticketNumber,
                'amount_paid'    => $ticketPrice,
                'tx_hash'        => $txHash,
            ]);

            // Update round members and collected funds
            $round->current_members = $ticketNumber;
            $round->total_collected += $ticketPrice;
            $round->save();

            $drawExecuted = false;
            $winnersCount = 0;

            // INSTANT DRAW TRIGGER: If 500th member just joined, run draw immediately! (NO CRON)
            if ($round->current_members >= 500) {
                self::executeInstantDraw($round);
                $drawExecuted = true;
                $winnersCount = 50;
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => $drawExecuted
                    ? "🎉 Congratulations! You took Ticket #{$ticketNumber}! The 500-member milestone was reached, and the Royal Jackpot was drawn INSTANTLY! Check the Hall of Fame & Royal Wallet."
                    : "🎉 Entry confirmed! Your Ticket Number is #{$ticketNumber} for {$poolDetail->pool_name} (Round #{$round->round_number}).",
                'data'    => [
                    'ticket_number'   => $ticketNumber,
                    'round_number'    => $round->round_number,
                    'pool_tier'       => $tier,
                    'slots_filled'    => $round->current_members,
                    'slots_remaining' => max(0, 500 - $round->current_members),
                    'draw_executed'   => $drawExecuted,
                    'winners_count'   => $winnersCount,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("EnterJackpot Exception: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to enter Royal Jackpot: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Executes the instant lucky draw when 500 members are reached:
     * - Shuffles 500 participants cryptographically
     * - Assigns top 50 ranks and prize amounts
     * - Automatically credits each winner's Royal Wallet
     * - Marks round as completed
     * - Instantly spawns Round (round_number + 1)
     */
    public static function executeInstantDraw(LuckyDrawRound $round)
    {
        Log::info("=== EXECUTING INSTANT ROYAL JACKPOT DRAW for Tier {$round->pool_tier}, Round #{$round->round_number} ===");

        $poolDetail = LuckyDrawDetail::where('pool_tier', $round->pool_tier)->first();
        if (!$poolDetail) return;

        // Fetch all 500 participants for this round
        $participants = LuckyDrawParticipant::where('round_id', $round->id)->get();
        if ($participants->count() < 1) return;

        $prizesConfig = $poolDetail->prizes_json;
        if (!is_array($prizesConfig)) {
            $prizesConfig = json_decode($prizesConfig, true) ?: [];
        }

        // Cryptographically secure shuffle using Fisher-Yates
        $participantArray = $participants->all();
        $totalCount = count($participantArray);

        for ($i = $totalCount - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $participantArray[$i];
            $participantArray[$i] = $participantArray[$j];
            $participantArray[$j] = $temp;
        }

        // Pick top 50 winners (or max available)
        $winnerCount = min(50, $totalCount);

        for ($rank = 1; $rank <= $winnerCount; $rank++) {
            $winnerPart = $participantArray[$rank - 1];

            // Determine prize for this rank
            $prize = 0.00;
            if ($rank === 1) {
                $prize = (float) ($prizesConfig['rank_1'] ?? 500.00);
            } elseif ($rank === 2) {
                $prize = (float) ($prizesConfig['rank_2'] ?? 300.00);
            } elseif ($rank === 3) {
                $prize = (float) ($prizesConfig['rank_3'] ?? 200.00);
            } elseif ($rank >= 4 && $rank <= 10) {
                $prize = (float) ($prizesConfig['rank_4_10'] ?? 100.00);
            } elseif ($rank >= 11 && $rank <= 25) {
                $prize = (float) ($prizesConfig['rank_11_25'] ?? 50.00);
            } elseif ($rank >= 26 && $rank <= 50) {
                $prize = (float) ($prizesConfig['rank_26_50'] ?? 25.00);
            }

            // 1. Record winner
            LuckyDrawWinner::create([
                'round_id'           => $round->id,
                'pool_tier'          => $round->pool_tier,
                'participant_id'     => $winnerPart->id,
                'userid'             => $winnerPart->userid,
                'wallet_address'     => $winnerPart->wallet_address,
                'ticket_number'      => $winnerPart->ticket_number,
                'rank'               => $rank,
                'prize_amount'       => $prize,
                'credited_to_wallet' => 1,
            ]);

            // 2. Automatically credit Winner's Royal Wallet! (Completely isolated from MLM capping/topup)
            $winnerUser = UserDetails::where('id', $winnerPart->userid)->first();
            if ($winnerUser) {
                $beforeBal = (float) ($winnerUser->royal_wallet ?? 0.00);
                $afterBal = $beforeBal + $prize;

                UserDetails::where('id', $winnerUser->id)->update([
                    'royal_wallet' => $afterBal
                ]);

                // 3. Log in Royal Wallet Ledger
                RoyalWalletLedger::create([
                    'userid'         => $winnerUser->id,
                    'type'           => 'win',
                    'amount'         => $prize,
                    'balance_before' => $beforeBal,
                    'balance_after'  => $afterBal,
                    'round_id'       => $round->id,
                    'claim_id'       => null,
                    'tx_hash'        => null,
                    'status'         => 1,
                    'notes'          => "Won Rank #{$rank} Prize in {$poolDetail->pool_name} (Round #{$round->round_number}, Ticket #{$winnerPart->ticket_number})",
                ]);
            }
        }

        // 4. Mark round as completed
        $round->status = 1;
        $round->drawn_at = now();
        $round->save();

        // 5. Update pool detail
        $poolDetail->increment('total_rounds_completed');
        $poolDetail->current_round = $round->round_number + 1;
        $poolDetail->save();

        // 6. IMMEDIATELY START BRAND NEW ROUND (Auto-Reset)
        // All 4 pools run continuously 24/7!
        $newRound = LuckyDrawRound::create([
            'pool_tier'       => $round->pool_tier,
            'round_number'    => $round->round_number + 1,
            'max_members'     => 500,
            'current_members' => 0,
            'ticket_price'    => $round->ticket_price,
            'total_collected' => 0.00,
            'status'          => 0,
            'started_at'      => now(),
        ]);

        Log::info("✅ Round #{$round->round_number} Completed! New Round #{$newRound->round_number} immediately spawned and active.");
    }

    /**
     * Request EIP-712 Signature to withdraw Royal Wallet balance from RoyalJackpotVault.
     * Reserves Royal Wallet balance atomically inside DB lock BEFORE signing to prevent
     * concurrent race conditions or multi-signature hoarding.
     */
    public function requestClaimSignature(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1.00',
            'wallet_address' => 'required|string|regex:/^0x[a-fA-F0-9]{40}$/',
        ]);

        $userId = Session::get('user.id');
        if (!$userId && \Auth::check()) {
            $userId = \Auth::id();
        }

        $userDetail = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or session expired.'], 401);
        }

        if ($userDetail->isDaoMember()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Currently withdrawals are paused, please try again later.'
            ], 403);
        }

        // First reconcile any prior pending/expired claim signatures against BSC on-chain state
        self::reconcilePendingRoyalClaims($userDetail->id);

        $requestedAmount = round((float) $request->amount, 2);
        $walletAddress = strtolower(trim($request->wallet_address));
        $vaultContract = env('ROYAL_JACKPOT_VAULT_ADDRESS') ?: '0x2A5DA0a78d7135A7d0Dfb4eD99430217ea8ab3ef';
        $chainId = 56;
        $privateKey = env('SIGNER_PRIVATE_KEY');
        if (empty($privateKey)) {
            Log::error("SIGNER_PRIVATE_KEY not set in .env");
            return response()->json(['status' => 'error', 'message' => 'Signer key configuration missing.'], 500);
        }

        // Convert to exact Wei (18 Decimals) without scientific notation or float rounding
        $amountStr = number_format($requestedAmount, 6, '.', '');
        $amountWei = bcmul($amountStr, '1000000000000000000', 0);
        $claimId = (string) (time() . random_int(10000, 99999));
        $expiry = (string) (time() + 180); // 3 minutes strict expiry window

        DB::beginTransaction();
        try {
            $user = UserDetails::where('id', $userDetail->id)->lockForUpdate()->first();
            $currentBalance = round((float) ($user->royal_wallet ?? 0.00), 2);

            if ($requestedAmount > $currentBalance) {
                DB::rollBack();
                return response()->json([
                    'status'  => 'error',
                    'message' => "Requested amount \${$requestedAmount} exceeds your available Royal Wallet balance of \${$currentBalance} USDT."
                ], 400);
            }

            $signResult = Eip712Helper::signRoyalJackpotClaim(
                $walletAddress,
                $amountWei,
                $claimId,
                $expiry,
                $vaultContract,
                $chainId,
                $privateKey
            );

            // Reserve & deduct Royal Wallet balance atomically before returning signature
            $afterBal = round(max(0, $currentBalance - $requestedAmount), 2);
            $user->royal_wallet = $afterBal;
            $user->save();

            RoyalWalletLedger::create([
                'userid'         => $user->id,
                'type'           => 'claim',
                'amount'         => $requestedAmount,
                'balance_before' => $currentBalance,
                'balance_after'  => $afterBal,
                'round_id'       => null,
                'claim_id'       => $claimId,
                'tx_hash'        => null,
                'status'         => 0, // 0 = Reserved & Pending On-Chain Confirmation
                'notes'          => "EIP-712 claim authorization issued for \${$requestedAmount} USDT (Expiry: {$expiry}).",
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'recipient'     => $walletAddress,
                    'amount'        => $requestedAmount,
                    'amountWei'     => $amountWei,
                    'claimId'       => $claimId,
                    'expiry'        => $expiry,
                    'signature'     => $signResult['signature'],
                    'vaultContract' => $vaultContract,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Royal Jackpot Claim Signature Generation Failed: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to generate cryptographic authorization: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Confirm Claim on Blockchain -> Verify BSC Receipt & Finalize Reserved Ledger Entry
     */
    public function confirmClaim(Request $request)
    {
        $request->validate([
            'amount'   => 'required|numeric|min:1.00',
            'claim_id' => 'required|string',
            'tx_hash'  => 'required|string',
        ]);

        $userId = Session::get('user.id');
        if (!$userId && \Auth::check()) {
            $userId = \Auth::id();
        }

        $userDetail = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if (!$userDetail) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or session expired.'], 401);
        }

        if ($userDetail->isDaoMember()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Currently withdrawals are paused, please try again later.'
            ], 403);
        }

        $txHash = strtolower(trim($request->tx_hash));
        $claimId = trim($request->claim_id);
        $amount = round((float) $request->amount, 2);
        $isDemo = (bool) env('DEMO_MODE', false);

        if (!$isDemo) {
            if (!preg_match('/^0x[a-fA-F0-9]{64}$/', $txHash)) {
                return response()->json(['status' => 'error', 'message' => 'Invalid BSC transaction hash.'], 400);
            }
            $executedOnChain = self::checkClaimExecutedOnBsc($claimId);
            if ($executedOnChain !== true) {
                return response()->json(['status' => 'error', 'message' => 'Prize claim not yet confirmed on BSC. Please wait a moment and refresh.'], 400);
            }
        }

        // Prevent duplicate processing
        if (RoyalWalletLedger::where('tx_hash', $txHash)->where('status', 1)->exists()) {
            return response()->json(['status' => 'success', 'message' => 'Claim already confirmed.']);
        }

        DB::beginTransaction();
        try {
            $user = UserDetails::where('id', $userDetail->id)->lockForUpdate()->first();
            $reservedLedger = RoyalWalletLedger::where('userid', $user->id)
                ->where('type', 'claim')
                ->where('claim_id', $claimId)
                ->lockForUpdate()
                ->first();

            if ($reservedLedger) {
                $finalAmount = (float) $reservedLedger->amount;
                $reservedLedger->tx_hash = $txHash;
                $reservedLedger->status = 1;
                $reservedLedger->notes = "Claimed \${$finalAmount} USDT prize via RoyalJackpotVault. Tx: {$txHash}";
                $reservedLedger->save();
                $afterBal = (float) ($user->royal_wallet ?? 0.00);
            } else {
                $beforeBal = (float) ($user->royal_wallet ?? 0.00);
                $finalAmount = min($amount, $beforeBal);
                $afterBal = max(0, $beforeBal - $finalAmount);
                $user->royal_wallet = $afterBal;
                $user->save();

                RoyalWalletLedger::create([
                    'userid'         => $user->id,
                    'type'           => 'claim',
                    'amount'         => $finalAmount,
                    'balance_before' => $beforeBal,
                    'balance_after'  => $afterBal,
                    'round_id'       => null,
                    'claim_id'       => $claimId,
                    'tx_hash'        => $txHash,
                    'status'         => 1,
                    'notes'          => "Claimed \${$finalAmount} USDT prize via RoyalJackpotVault. Tx: {$txHash}",
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "🎉 \${$finalAmount} USDT successfully delivered to your Web3 wallet!",
                'data'    => [
                    'new_balance' => $afterBal,
                    'tx_hash'     => $txHash,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Confirm Royal Claim Exception: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to confirm claim: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Dedicated Pool Details & Joining Page
     */
    public function poolPage($tier)
    {
        $tier = (int) $tier;
        if (!in_array($tier, [1, 2, 3, 4])) {
            return redirect('/User/RoyalJackpot');
        }

        self::seedJackpotDetails();

        $userId = Session::get('user.id');
        if (!$userId && \Auth::check()) {
            $userId = \Auth::id();
        }

        $userDetail = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
        if ($userDetail) {
            self::reconcilePendingRoyalClaims($userDetail->id);
            $userDetail->refresh();
        }
        $eligibility = self::checkUserStakingEligibility($userId);

        $pool = LuckyDrawDetail::where('pool_tier', $tier)->first();
        if (!$pool) {
            return redirect('/User/RoyalJackpot');
        }

        $activeRound = LuckyDrawRound::where('pool_tier', $tier)->where('status', 0)->first();
        if (!$activeRound) {
            $activeRound = LuckyDrawRound::create([
                'pool_tier'       => $tier,
                'round_number'    => $pool->current_round ?: 1,
                'max_members'     => 500,
                'current_members' => 0,
                'ticket_price'    => $pool->ticket_price,
                'total_collected' => 0.00,
                'status'          => 0,
                'started_at'      => now(),
            ]);
        }

        $slotsFilled     = (int) $activeRound->current_members;
        $slotsRemaining  = max(0, 500 - $slotsFilled);
        $percentFilled   = round(($slotsFilled / 500) * 100, 1);

        $myTicketsInRound = $userDetail
            ? LuckyDrawParticipant::where('round_id', $activeRound->id)->where('userid', $userDetail->id)->orderBy('ticket_number', 'asc')->get()
            : collect();

        $myTotalTicketsAllRounds = $userDetail
            ? LuckyDrawParticipant::where('pool_tier', $tier)->where('userid', $userDetail->id)->count()
            : 0;

        // Latest members who joined this round (masked wallets only)
        $recentJoins = LuckyDrawParticipant::where('round_id', $activeRound->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Past winners of this pool
        $pastWinners = LuckyDrawWinner::with(['round'])
            ->where('pool_tier', $tier)
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        $completedRounds = LuckyDrawRound::where('pool_tier', $tier)
            ->where('status', 1)
            ->orderBy('drawn_at', 'desc')
            ->take(10)
            ->get();

        $royalWalletBalance = (float) ($userDetail->royal_wallet ?? 0.00);

        $contracts = [
            'depositContract' => env('ROYAL_JACKPOT_DEPOSIT_ADDRESS') ?: '0xE4c8EF7f058051512e228dDDAC8C652fD6bc4e41',
            'vaultContract'   => env('ROYAL_JACKPOT_VAULT_ADDRESS') ?: '0x2A5DA0a78d7135A7d0Dfb4eD99430217ea8ab3ef',
            'usdtToken'       => env('USDT_TOKEN_ADDRESS') ?: '0x55d398326f99059fF775485246999027B3197955',
            'chainId'         => 56,
            'demoMode'        => (bool) env('DEMO_MODE', false),
        ];

        return view('user.royaljackpotpool', compact(
            'pool',
            'activeRound',
            'slotsFilled',
            'slotsRemaining',
            'percentFilled',
            'myTicketsInRound',
            'myTotalTicketsAllRounds',
            'recentJoins',
            'pastWinners',
            'completedRounds',
            'eligibility',
            'royalWalletBalance',
            'contracts'
        ));
    }

    /**
     * Polling API for Live Real-Time Slot Progress & Notifications
     */
    public function getLiveStats()
    {
        $userId = Session::get('user.id');
        $pools = LuckyDrawDetail::where('status', 1)->orderBy('pool_tier', 'asc')->get();
        $stats = [];

        foreach ($pools as $pool) {
            $activeRound = LuckyDrawRound::where('pool_tier', $pool->pool_tier)
                ->where('status', 0)
                ->first();

            $slotsFilled = $activeRound ? $activeRound->current_members : 0;
            $stats[$pool->pool_tier] = [
                'tier'            => $pool->pool_tier,
                'round_number'    => $activeRound ? $activeRound->round_number : 1,
                'slots_filled'    => $slotsFilled,
                'slots_remaining' => max(0, 500 - $slotsFilled),
                'percentage'      => round(($slotsFilled / 500) * 100, 1),
                'ticket_price'    => (float) $pool->ticket_price,
            ];
        }

        $royalWallet = 0.00;
        if ($userId) {
            $u = UserDetails::where('id', $userId)->orWhere('userid', $userId)->first();
            if ($u) {
                self::reconcilePendingRoyalClaims($u->id);
                $u->refresh();
                $royalWallet = (float) ($u->royal_wallet ?? 0.00);
            }
        }

        return response()->json([
            'status'       => 'success',
            'pools'        => $stats,
            'royal_wallet' => $royalWallet,
        ]);
    }

    /**
     * Lossless arbitrary-precision hexadecimal to decimal string converter using BCMath.
     */
    private static function hexToBcDec($hex)
    {
        $clean = ltrim(strtolower(preg_replace('/^0x/', '', (string) $hex)), '0');
        if ($clean === '') {
            return '0';
        }
        $dec = '0';
        $len = strlen($clean);
        for ($i = 0; $i < $len; $i++) {
            $dec = bcadd(bcmul($dec, '16', 0), (string) hexdec($clean[$i]), 0);
        }
        return $dec;
    }

    /**
     * Query RoyalJackpotVault.isClaimExecuted(claimId) on BSC Mainnet.
     * Returns true if executed, false if confirmed unexecuted, or null if RPC unreachable.
     */
    public static function checkClaimExecutedOnBsc($claimId)
    {
        $vault = strtolower(env('ROYAL_JACKPOT_VAULT_ADDRESS') ?: '0x2A5DA0a78d7135A7d0Dfb4eD99430217ea8ab3ef');
        if (empty($vault) || empty($claimId) || !ctype_digit((string) $claimId)) {
            return null;
        }

        $selector = substr(\kornrunner\Keccak::hash('isClaimExecuted(uint256)', 256), 0, 8);
        // Convert decimal claimId string to 64-char hex
        $dec = (string) $claimId;
        $hexId = '';
        while (bccomp($dec, '0', 0) > 0) {
            $rem = (int) bcmod($dec, '16');
            $hexId = dechex($rem) . $hexId;
            $dec = bcdiv($dec, '16', 0);
        }
        $data = '0x' . $selector . str_pad($hexId ?: '0', 64, '0', STR_PAD_LEFT);

        $rpcEndpoints = [
            "https://bsc-dataseed.binance.org/",
            "https://bsc-dataseed1.defibit.io/",
            "https://1rpc.io/bnb",
        ];

        $payload = json_encode([
            'jsonrpc' => '2.0',
            'method'  => 'eth_call',
            'params'  => [['to' => $vault, 'data' => $data], 'latest'],
            'id'      => 1,
        ]);

        foreach ($rpcEndpoints as $rpcUrl) {
            $ch = curl_init($rpcUrl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 4);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $json = json_decode($response, true);
                if (isset($json['result']) && is_string($json['result']) && strpos($json['result'], '0x') === 0 && strlen($json['result']) >= 66) {
                    $val = self::hexToBcDec($json['result']);
                    return ($val === '1');
                }
            }
        }

        return null;
    }

    /**
     * Reconcile reserved (status = 0) Royal Wallet claim authorizations against BSC on-chain state:
     * - If executed on-chain -> finalize as status = 1.
     * - If older than 4 minutes (past 3-min on-chain expiry) and confirmed unexecuted -> refund Royal Wallet & mark status = 2.
     */
    public static function reconcilePendingRoyalClaims($userId)
    {
        if (empty($userId)) {
            return;
        }

        $pendingRows = RoyalWalletLedger::where('userid', $userId)
            ->where('type', 'claim')
            ->where('status', 0)
            ->get();

        if ($pendingRows->isEmpty()) {
            return;
        }

        foreach ($pendingRows as $row) {
            $executed = self::checkClaimExecutedOnBsc($row->claim_id);
            if ($executed === true) {
                $row->status = 1;
                $row->notes = trim(($row->notes ?? '') . ' [Auto-confirmed via BSC Vault state]');
                $row->save();
                continue;
            }

            // Refund ONLY when confirmed unexecuted on-chain AND past 4-minute safety buffer (signature expiry = 3 min)
            $ageSeconds = $row->created_at ? now()->diffInSeconds($row->created_at, true) : 0;
            if ($executed === false && $ageSeconds >= 240) {
                DB::beginTransaction();
                try {
                    $lockedRow = RoyalWalletLedger::where('id', $row->id)->lockForUpdate()->first();
                    if ($lockedRow && (int) $lockedRow->status === 0) {
                        $user = UserDetails::where('id', $userId)->lockForUpdate()->first();
                        if ($user) {
                            $user->royal_wallet = round((float) ($user->royal_wallet ?? 0) + (float) $lockedRow->amount, 2);
                            $user->save();
                        }
                        $lockedRow->status = 2;
                        $lockedRow->notes = trim(($lockedRow->notes ?? '') . ' [Expired unexecuted on BSC — Balance restored]');
                        $lockedRow->save();
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Reconcile Royal Claim Refund Error: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Strictly verify that the deposit transaction succeeded on Binance Smart Chain.
     * Decodes both `JackpotDeposited` AND ERC-20 `Transfer` events using lossless BCMath!
     */
    public static function verifyJackpotDepositOnBsc($txHash, $expectedSender, $ticketPrice, $expectedTier = null)
    {
        $expectedContract = strtolower(env('ROYAL_JACKPOT_DEPOSIT_ADDRESS') ?: '0xE4c8EF7f058051512e228dDDAC8C652fD6bc4e41');
        $expectedUsdt     = strtolower(env('USDT_TOKEN_ADDRESS') ?: '0x55d398326f99059fF775485246999027B3197955');

        $rpcEndpoints = [
            "https://bsc-dataseed.binance.org/",
            "https://bsc-dataseed1.defibit.io/",
            "https://bsc-dataseed1.ninicoin.io/",
            "https://1rpc.io/bnb",
            "https://bsc.meowrpc.com"
        ];

        $payloadReceipt = json_encode([
            'jsonrpc' => '2.0',
            'method'  => 'eth_getTransactionReceipt',
            'params'  => [$txHash],
            'id'      => 1
        ]);

        $receipt = null;
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            foreach ($rpcEndpoints as $rpcUrl) {
                $ch = curl_init($rpcUrl);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadReceipt);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 6);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);

                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['result']) && !empty($data['result'])) {
                        $receipt = $data['result'];
                        break 2;
                    }
                }
            }
            if ($attempt < $maxAttempts) {
                sleep(2);
            }
        }

        if (!$receipt) {
            return ['valid' => false, 'message' => 'Transaction receipt not found on BSC. Please wait for block confirmation and retry.'];
        }

        \Log::info("Royal Jackpot on-chain receipt for {$txHash}:", [
            'to' => $receipt['to'] ?? null,
            'from' => $receipt['from'] ?? null,
            'status' => $receipt['status'] ?? null,
            'logs_count' => count($receipt['logs'] ?? []),
        ]);

        // Status check: 0x1 = confirmed success
        if (!isset($receipt['status']) || $receipt['status'] !== '0x1') {
            return ['valid' => false, 'message' => 'Blockchain transaction reverted or failed. Ticket was not issued.'];
        }

        // ========================================================
        // SMART CONTRACT ABI EVENT VERIFICATION
        // ========================================================
        // 1. ERC-20 Transfer Event: Transfer(address indexed from, address indexed to, uint256 value)
        $erc20TransferTopic  = '0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef';
        // 2. RoyalJackpotDeposit Event: JackpotDeposited(address indexed user, uint256 indexed poolTier, uint256 indexed roundId, uint256 amount, uint256 timestamp)
        $jackpotDepositTopic = '0x3fe42b4055a2521f57b410326011af0ad4f668fbec5995b41ddad162bbafd8de';
        // 3. Approval Event: Approval(address indexed owner, address indexed spender, uint256 value)
        $erc20ApprovalTopic  = '0x8c5be1e5ebec7d5bd14f71427d1e84f3dd0314c0f7b2291e5b200ac8c7c3b925';

        $foundDepositEvent = false;
        $foundUsdtTransferToContract = false;
        $onlyApprovalFound = false;
        $actualAmountUsdt = 0.0;
        $onChainTier = null;
        $actualSender = null;

        $logs = $receipt['logs'] ?? [];

        foreach ($logs as $log) {
            $logAddr = strtolower($log['address'] ?? '');
            $topics  = $log['topics'] ?? [];
            $topic0  = strtolower($topics[0] ?? '');

            // Check if JackpotDeposited event was emitted by official Deposit contract
            if ($logAddr === $expectedContract && $topic0 === $jackpotDepositTopic) {
                $foundDepositEvent = true;
                if (isset($topics[1])) {
                    $actualSender = '0x' . substr(strtolower($topics[1]), 26);
                }
                if (isset($topics[2])) {
                    $onChainTier = (int) self::hexToBcDec($topics[2]);
                }
            }

            // Check if USDT Transfer event was sent to official Deposit contract
            if ($logAddr === $expectedUsdt && $topic0 === $erc20TransferTopic) {
                $recipient = isset($topics[2]) ? ('0x' . substr(strtolower($topics[2]), 26)) : '';
                if ($recipient === $expectedContract) {
                    $foundUsdtTransferToContract = true;
                    if (isset($topics[1]) && empty($actualSender)) {
                        $actualSender = '0x' . substr(strtolower($topics[1]), 26);
                    }
                    if (!empty($log['data']) && $log['data'] !== '0x') {
                        $weiDec = self::hexToBcDec($log['data']);
                        $actualAmountUsdt = round((float) bcdiv($weiDec, '1000000000000000000', 6), 4);
                    }
                }
            }

            // Detect if this is only an Approval transaction
            if ($logAddr === $expectedUsdt && $topic0 === $erc20ApprovalTopic) {
                $spender = isset($topics[2]) ? ('0x' . substr(strtolower($topics[2]), 26)) : '';
                if ($spender === $expectedContract) {
                    $onlyApprovalFound = true;
                }
            }
        }

        if (!$foundDepositEvent || !$foundUsdtTransferToContract) {
            if ($onlyApprovalFound) {
                return [
                    'valid' => false,
                    'message' => 'This was a USDT Approval transaction. Please confirm the second prompt in MetaMask to complete the ticket purchase!'
                ];
            }
            return [
                'valid' => false,
                'message' => 'Transaction did not execute depositJackpot on the official Royal Jackpot Deposit contract (' . substr($expectedContract, 0, 8) . '...).'
            ];
        }

        // Strict Pool Tier Check
        if ($expectedTier !== null && $onChainTier !== null && (int) $expectedTier !== (int) $onChainTier) {
            return [
                'valid' => false,
                'message' => "On-chain pool tier ({$onChainTier}) does not match requested pool tier ({$expectedTier})."
            ];
        }

        // Strict Ticket Price Check
        $expectedPrice = round((float) $ticketPrice, 2);
        if ($expectedPrice > 0 && ($actualAmountUsdt + 0.01) < $expectedPrice) {
            return [
                'valid' => false,
                'message' => "On-chain USDT payment (\${$actualAmountUsdt}) is less than required ticket price (\${$expectedPrice})."
            ];
        }

        // Sender check
        if (!empty($expectedSender) && !empty($actualSender)) {
            if (strtolower($actualSender) !== strtolower($expectedSender)) {
                return ['valid' => false, 'message' => 'Transaction sender (' . substr($actualSender, 0, 6) . '...) does not match your connected wallet.'];
            }
        }

        return ['valid' => true, 'receipt' => $receipt, 'amount' => $actualAmountUsdt];
    }
}

