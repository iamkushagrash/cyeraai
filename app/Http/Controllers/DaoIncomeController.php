<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DaoDetail;
use App\DaoDistribution;
use App\DaoIncome;
use App\UserDetails;
use App\StackingDeposite;
use App\ProfileStore;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DaoIncomeController extends Controller
{
    /**
     * Ensure default DAO Pools exist in database.
     */
    public static function seedDaoDetails()
    {
        $tiers = [
            [
                'dao_type'           => 1,
                'dao_name'           => 'Platinum DAO Member',
                'package_amount'     => 3333.00,
                'max_members'        => 100,
                'rank_required'      => 2, // V2 Rank
                'days_limit'         => 90,
                'pool_percent'       => 2.50,
                'capping_multiplier' => 3.0,
                'status'             => 1,
            ],
            [
                'dao_type'           => 2,
                'dao_name'           => 'Golden DAO Member',
                'package_amount'     => 5555.00,
                'max_members'        => 100,
                'rank_required'      => 3, // V3 Rank
                'days_limit'         => 90,
                'pool_percent'       => 3.00,
                'capping_multiplier' => 3.5,
                'status'             => 1,
            ],
            [
                'dao_type'           => 3,
                'dao_name'           => 'Diamond DAO Member',
                'package_amount'     => 10000.00,
                'max_members'        => 50,
                'rank_required'      => 3, // V3 Rank
                'days_limit'         => 90,
                'pool_percent'       => 4.00,
                'capping_multiplier' => 4.0,
                'status'             => 1,
            ],
        ];

        foreach ($tiers as $t) {
            DaoDetail::firstOrCreate(
                ['dao_type' => $t['dao_type']],
                $t
            );
        }
    }

    /**
     * Weekly DAO Pool Income Distribution Cron
     * Schedule: Mondays at 01:55 AM (Asia/Kolkata)
     * Tiers:
     * - Diamond DAO (4.0% Turnover, 50 Members Max, 4X Cap)
     * - Golden DAO (3.0% Turnover, 100 Members Max, 3.5X Cap)
     * - Platinum DAO (2.5% Turnover, 100 Members Max, 3X Cap)
     */
    public function weeklyDaoDistribution()
    {
        Log::info('--- Starting Weekly DAO Income Distribution ---');
        self::seedDaoDetails();

        $weekStart = Carbon::now()->subWeek()->startOfWeek();
        $weekEnd   = Carbon::now()->subWeek()->endOfWeek();

        // Check if already executed for this week
        $alreadyRun = DaoDistribution::whereBetween('week_start', [
            $weekStart->copy()->subMinutes(10),
            $weekStart->copy()->addMinutes(10)
        ])->exists();

        if ($alreadyRun) {
            Log::info("Weekly DAO Distribution already executed for week starting {$weekStart}");
            return;
        }

        // Calculate Last Week Global Turnover from active USDT topups
        $totalWeeklyBusiness = (float) StackingDeposite::where('status', 1)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->sum('usdt');

        Log::info("Weekly Global Turnover for DAO Pools ({$weekStart->toDateString()} to {$weekEnd->toDateString()}): \${$totalWeeklyBusiness} USDT");

        $profileStore = ProfileStore::where('id', 1)->first();
        $tokenPrice = ($profileStore && (float)$profileStore->price > 0) ? (float)$profileStore->price : 1.0;

        $tiers = DaoDetail::where('status', 1)->orderBy('dao_type', 'desc')->get();

        foreach ($tiers as $tier) {
            // Slot Full Requirement Check (Income starts ONLY when pool slots are 100% full)
            $totalPoolMembers = UserDetails::where('is_dao', $tier->dao_type)->where('userstatus', 1)->count();
            if ($totalPoolMembers < (int)$tier->max_members) {
                Log::info("DAO Tier {$tier->dao_name} skipped: Slots not full yet ({$totalPoolMembers}/{$tier->max_members}). Weekly income will start once 100% full.");
                continue;
            }

            $poolAmount = ($totalWeeklyBusiness * (float)$tier->pool_percent) / 100.0;

            // Fetch active candidates who purchased this DAO tier
            $candidates = UserDetails::where('is_dao', $tier->dao_type)
                ->where('userstatus', 1)
                ->get();

            $eligibleUserIds = [];

            foreach ($candidates as $user) {
                // Check & Sync Rank
                RankIncomeController::checkAndSyncUserRank($user->id);
                $user = $user->fresh();

                $userCreated = $user->user() ? Carbon::parse($user->user()->created_at) : Carbon::parse($user->created_at ?? now());
                $daysReg = $userCreated->diffInDays(now());

                // Criteria 1: 90 Days Registration Limit
                if ($daysReg > (int)$tier->days_limit) {
                    Log::info("DAO Member {$user->id} skipped: Registration age ({$daysReg} days) exceeds 90 days limit.");
                    continue;
                }

                // Criteria 2: Rank Requirement (V2 for Platinum, V3 for Golden/Diamond)
                if ((int)$user->rank_level < (int)$tier->rank_required) {
                    Log::info("DAO Member {$user->id} skipped: Rank V{$user->rank_level} does not meet V{$tier->rank_required} requirement.");
                    continue;
                }

                // Criteria 3: Individual DAO Income Capping
                $maxCap = (float)$tier->package_amount * (float)$tier->capping_multiplier;
                $totalReceived = (float) DaoIncome::where('userid', $user->id)
                    ->where('dao_type', $tier->dao_type)
                    ->sum('amt_usdt');

                if ($totalReceived >= $maxCap) {
                    Log::info("DAO Member {$user->id} skipped: Capping limit \${$maxCap} reached.");
                    continue;
                }

                $eligibleUserIds[] = $user->id;
            }

            $qualifiedCount = count($eligibleUserIds);
            $perMemberPayout = ($qualifiedCount > 0 && $poolAmount > 0) ? ($poolAmount / $qualifiedCount) : 0.00;

            $distribution = DaoDistribution::create([
                'dao_type'              => $tier->dao_type,
                'dao_name'              => $tier->dao_name,
                'week_start'            => $weekStart,
                'week_end'              => $weekEnd,
                'total_weekly_business' => $totalWeeklyBusiness,
                'pool_percent'          => $tier->pool_percent,
                'pool_amount'           => $poolAmount,
                'qualified_members'     => $qualifiedCount,
                'per_member_payout'     => $perMemberPayout,
                'status'                => 2, // Distributed
            ]);

            if ($perMemberPayout > 0 && !empty($eligibleUserIds)) {
                foreach ($eligibleUserIds as $uId) {
                    $maxCap = (float)$tier->package_amount * (float)$tier->capping_multiplier;
                    $totalReceived = (float) DaoIncome::where('userid', $uId)
                        ->where('dao_type', $tier->dao_type)
                        ->sum('amt_usdt');
                    $remCap = max(0.00, $maxCap - $totalReceived);

                    $actualPayout = min($perMemberPayout, $remCap);
                    if ($actualPayout <= 0) continue;

                    $tokenAmt = $actualPayout / $tokenPrice;

                    DaoIncome::create([
                        'userid'          => $uId,
                        'dao_type'        => $tier->dao_type,
                        'dao_name'        => $tier->dao_name,
                        'distribution_id' => $distribution->id,
                        'amount'          => $tokenAmt,
                        'remaining'       => $tokenAmt,
                        'amt_usdt'        => $actualPayout,
                        'remaining_usdt'  => $actualPayout,
                        'status'          => 0,
                    ]);

                    Log::info("Weekly DAO Payout: User ID {$uId} received \${$actualPayout} USDT in {$tier->dao_name}");
                }
            }

            Log::info("DAO Tier {$tier->dao_name} Distribution Finished: Turnover=\${$totalWeeklyBusiness}, Pool=\${$poolAmount}, Qualified={$qualifiedCount}, Per Share=\${$perMemberPayout}");
        }

        Log::info('--- Weekly DAO Income Distribution Completed Successfully ---');
    }

    /**
     * User Panel View for DAO Incomes & Overview
     */
    public function showDaoIncomePage(Request $request)
    {
        $userId = Session::get('user.id');
        $userDetail = UserDetails::where('id', $userId)->first();
        if (!$userDetail) {
            return redirect('/login');
        }

        self::seedDaoDetails();

        // Sync live rank first
        RankIncomeController::checkAndSyncUserRank($userId);
        $userDetail = $userDetail->fresh();

        $daoTiers = DaoDetail::where('status', 1)->orderBy('dao_type', 'asc')->get();

        // Income Totals
        $platinumTotalUsdt = (float) DaoIncome::where('userid', $userId)->where('dao_type', 1)->sum('amt_usdt');
        $goldenTotalUsdt   = (float) DaoIncome::where('userid', $userId)->where('dao_type', 2)->sum('amt_usdt');
        $diamondTotalUsdt  = (float) DaoIncome::where('userid', $userId)->where('dao_type', 3)->sum('amt_usdt');
        $grandTotalUsdt    = $platinumTotalUsdt + $goldenTotalUsdt + $diamondTotalUsdt;

        $incomes = DaoIncome::with('distribution')
            ->where('userid', $userId)
            ->orderBy('id', 'desc')
            ->get();

        $recentDistributions = DaoDistribution::orderBy('id', 'desc')
            ->take(10)
            ->get();

        return view('user.incomedao', compact(
            'userDetail',
            'daoTiers',
            'platinumTotalUsdt',
            'goldenTotalUsdt',
            'diamondTotalUsdt',
            'grandTotalUsdt',
            'incomes',
            'recentDistributions'
        ));
    }
}
