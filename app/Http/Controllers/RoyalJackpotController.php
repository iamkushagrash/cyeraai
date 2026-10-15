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
            'depositContract' => env('ROYAL_JACKPOT_DEPOSIT_ADDRESS', '0x1C27Be1bAf6a8946755490a61250fD0e9D6A1234'),
            'vaultContract'   => env('ROYAL_JACKPOT_VAULT_ADDRESS', '0x7B99cB95A85B3De549aE29b626E6fB2983756789'),
            'usdtToken'       => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'chainId'         => 56,
            'demoMode'        => (bool) env('DEMO_MODE', true),
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

        // Prevent duplicate transaction hash
        $txHash = $request->tx_hash ? strtolower(trim($request->tx_hash)) : ('demo_' . time() . '_' . rand(1000, 9999));
        if ($request->tx_hash && LuckyDrawParticipant::where('tx_hash', $txHash)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'This transaction hash has already been registered.'], 400);
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
     * Request EIP-712 Signature to withdraw Royal Wallet balance from RoyalJackpotVault
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

        $requestedAmount = (float) $request->amount;
        $currentBalance = (float) ($userDetail->royal_wallet ?? 0.00);

        if ($requestedAmount > $currentBalance) {
            return response()->json([
                'status'  => 'error',
                'message' => "Requested amount \${$requestedAmount} exceeds your available Royal Wallet balance of \${$currentBalance} USDT."
            ], 400);
        }

        $walletAddress = strtolower(trim($request->wallet_address));
        $vaultContract = env('ROYAL_JACKPOT_VAULT_ADDRESS', '0x7B99cB95A85B3De549aE29b626E6fB2983756789');
        $chainId = 56;
        $privateKey = env('SIGNER_PRIVATE_KEY', '38765232db1a84b6571252884f7168205f3207242cd31f99ab4e76ad76f5a67b');

        // Convert to Wei (18 Decimals)
        $amountWei = bcmul((string) $requestedAmount, '1000000000000000000', 0);
        $claimId = (string) (time() . rand(1000, 9999));
        $expiry = (string) (time() + 600); // 10 minutes

        try {
            $signResult = Eip712Helper::signRoyalJackpotClaim(
                $walletAddress,
                $amountWei,
                $claimId,
                $expiry,
                $vaultContract,
                $chainId,
                $privateKey
            );

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
            Log::error("Royal Jackpot Claim Signature Generation Failed: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to generate cryptographic authorization: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Confirm Claim on Blockchain -> Deduct Royal Wallet Balance & Audit
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

        $txHash = strtolower(trim($request->tx_hash));
        $claimId = trim($request->claim_id);
        $amount = (float) $request->amount;

        // Prevent duplicate processing
        if (RoyalWalletLedger::where('tx_hash', $txHash)->exists()) {
            return response()->json(['status' => 'success', 'message' => 'Claim already confirmed.']);
        }

        DB::beginTransaction();
        try {
            $user = UserDetails::where('id', $userDetail->id)->lockForUpdate()->first();
            $beforeBal = (float) ($user->royal_wallet ?? 0.00);
            $deductAmount = min($amount, $beforeBal);
            $afterBal = max(0, $beforeBal - $deductAmount);

            $user->royal_wallet = $afterBal;
            $user->save();

            // Record in Ledger
            RoyalWalletLedger::create([
                'userid'         => $user->id,
                'type'           => 'claim',
                'amount'         => $deductAmount,
                'balance_before' => $beforeBal,
                'balance_after'  => $afterBal,
                'round_id'       => null,
                'claim_id'       => $claimId,
                'tx_hash'        => $txHash,
                'status'         => 1,
                'notes'          => "Claimed \${$deductAmount} USDT prize via RoyalJackpotVault. Tx: {$txHash}",
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => "🎉 \${$deductAmount} USDT successfully delivered to your Web3 wallet!",
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
            if ($u) $royalWallet = (float) ($u->royal_wallet ?? 0.00);
        }

        return response()->json([
            'status'       => 'success',
            'pools'        => $stats,
            'royal_wallet' => $royalWallet,
        ]);
    }
}
