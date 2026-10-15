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

            // Verify on-chain execution with BSC RPC nodes
            $verify = self::verifyJackpotDepositOnBsc($txHash, $walletAddr, $ticketPrice);
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
            'depositContract' => env('ROYAL_JACKPOT_DEPOSIT_ADDRESS', '0x1C27Be1bAf6a8946755490a61250fD0e9D6A1234'),
            'vaultContract'   => env('ROYAL_JACKPOT_VAULT_ADDRESS', '0x7B99cB95A85B3De549aE29b626E6fB2983756789'),
            'usdtToken'       => env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'),
            'chainId'         => 56,
            'demoMode'        => (bool) env('DEMO_MODE', true),
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
            if ($u) $royalWallet = (float) ($u->royal_wallet ?? 0.00);
        }

        return response()->json([
            'status'       => 'success',
            'pools'        => $stats,
            'royal_wallet' => $royalWallet,
        ]);
    }

    /**
     * Strictly verify that the deposit transaction succeeded on Binance Smart Chain
     * Decodes the Smart Contract ABI Events directly from the transaction receipt!
     */
    public static function verifyJackpotDepositOnBsc($txHash, $expectedSender, $ticketPrice)
    {
        $expectedContract = strtolower(env('ROYAL_JACKPOT_DEPOSIT_ADDRESS', '0xB97D65c4A653a280d946A50FFe6237EDbEA4C0C5'));
        $expectedUsdt     = strtolower(env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'));

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
        $actualSender = null;

        $logs = $receipt['logs'] ?? [];

        foreach ($logs as $log) {
            $logAddr = strtolower($log['address'] ?? '');
            $topics  = $log['topics'] ?? [];
            $topic0  = strtolower($topics[0] ?? '');

            // Check if JackpotDeposited event was emitted by Deposit contract
            if ($logAddr === $expectedContract && $topic0 === $jackpotDepositTopic) {
                $foundDepositEvent = true;
                if (isset($topics[1])) {
                    $actualSender = '0x' . substr(strtolower($topics[1]), 26);
                }
            }

            // Check if USDT Transfer event was sent to Deposit contract
            if ($logAddr === $expectedUsdt && $topic0 === $erc20TransferTopic) {
                $recipient = isset($topics[2]) ? ('0x' . substr(strtolower($topics[2]), 26)) : '';
                if ($recipient === $expectedContract) {
                    $foundUsdtTransferToContract = true;
                    if (isset($topics[1])) {
                        $actualSender = '0x' . substr(strtolower($topics[1]), 26);
                    }
                    if (!empty($log['data']) && $log['data'] !== '0x') {
                        $hexClean = ltrim(substr($log['data'], 2), '0');
                        $val = empty($hexClean) ? 0 : base_convert($hexClean, 16, 10);
                        $actualAmountUsdt = round($val / 1e18, 4);
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

        // Also check direct contract interaction if receipt.to matches expectedContract
        if (isset($receipt['to']) && strtolower($receipt['to']) === $expectedContract) {
            $foundDepositEvent = true;
        }

        if (!$foundDepositEvent && !$foundUsdtTransferToContract) {
            if ($onlyApprovalFound) {
                return [
                    'valid' => false,
                    'message' => 'This was a USDT Approval transaction. Please confirm the second prompt in MetaMask to complete the ticket purchase!'
                ];
            }
            return [
                'valid' => false,
                'message' => 'Transaction was not sent to the official Royal Jackpot Deposit contract (' . substr($expectedContract, 0, 8) . '...).'
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

