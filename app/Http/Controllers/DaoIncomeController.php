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
     * Ensure fresh default DAO Pools exist in database.
     * Diamond: $10,000 | 4X Cap | 4.0% Pool | Rank V5 in 60 Days | 50 Members
     * Golden:  $5,555  | 3.5X Cap | 3.0% Pool | Rank V4 in 60 Days | 100 Members
     * Platinum: $3,333 | 3.0X Cap | 2.5% Pool | Rank V3 in 60 Days | 100 Members
     * Start Date: 5 Nov 2026
     */
    public static function seedDaoDetails()
    {
        $tiers = [
            [
                'dao_type'           => 1,
                'dao_name'           => 'Platinum DAO Member',
                'package_amount'     => 3333.00,
                'max_members'        => 100,
                'rank_required'      => 3, // V3 Rank
                'days_limit'         => 60, // 60 Days from 5 Nov 2026
                'pool_percent'       => 2.50,
                'capping_multiplier' => 3.0,
                'start_date'         => '2026-11-05',
                'status'             => 1,
            ],
            [
                'dao_type'           => 2,
                'dao_name'           => 'Golden DAO Member',
                'package_amount'     => 5555.00,
                'max_members'        => 100,
                'rank_required'      => 4, // V4 Rank
                'days_limit'         => 60, // 60 Days from 5 Nov 2026
                'pool_percent'       => 3.00,
                'capping_multiplier' => 3.5,
                'start_date'         => '2026-11-05',
                'status'             => 1,
            ],
            [
                'dao_type'           => 3,
                'dao_name'           => 'Diamond DAO Member',
                'package_amount'     => 10000.00,
                'max_members'        => 50,
                'rank_required'      => 5, // V5 Rank
                'days_limit'         => 60, // 60 Days from 5 Nov 2026
                'pool_percent'       => 4.00,
                'capping_multiplier' => 4.0,
                'start_date'         => '2026-11-05',
                'status'             => 1,
            ],
        ];

        foreach ($tiers as $t) {
            DaoDetail::updateOrCreate(
                ['dao_type' => $t['dao_type']],
                $t
            );
        }
    }

    /**
     * Weekly DAO Pool Income Distribution Cron
     * Schedule: Sundays at 23:55 PM (Asia/Kolkata)
     * Starts: 5 Nov 2026
     * First distribution: 5 Nov 2026 to first Sunday (8 Nov 2026)
     * Subsequent: Sunday to Sunday (7 days)
     * Tiers:
     * - Diamond DAO (4.0% Turnover, 50 Members Max, 4X Cap, V5 Rank in 60d)
     * - Golden DAO (3.0% Turnover, 100 Members Max, 3.5X Cap, V4 Rank in 60d)
     * - Platinum DAO (2.5% Turnover, 100 Members Max, 3X Cap, V3 Rank in 60d)
     */
    public function weeklyDaoDistribution()
    {
        Log::info('--- Starting Weekly DAO Income Distribution ---');
        self::seedDaoDetails();

        $poolStartDate = Carbon::parse('2026-11-05 00:00:00');
        if (Carbon::now()->lt($poolStartDate)) {
            Log::info("Weekly DAO Distribution skipped: Pool starts on 5 Nov 2026. Current date is before start date.");
            return;
        }

        // Determine cycle window
        $lastDist = DaoDistribution::orderBy('week_end', 'desc')->first();

        if (!$lastDist) {
            // First distribution: 5 Nov 2026 (Thursday) to first Sunday 8 Nov 2026 23:59:59
            $weekStart = $poolStartDate->copy();
            $firstSunday = Carbon::parse('2026-11-08 23:59:59');
            $weekEnd = Carbon::now()->gte($firstSunday) ? $firstSunday : Carbon::now();
            $periodDays = max(1, $weekStart->diffInDays($weekEnd) + 1);
        } else {
            $weekStart = Carbon::parse($lastDist->week_end)->addSecond();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $periodDays = 7;
        }

        // Avoid premature run
        if (Carbon::now()->lt($weekEnd)) {
            Log::info("Weekly DAO Distribution: Waiting for period end {$weekEnd->toDateTimeString()}.");
            return;
        }

        // Check if already executed for this weekEnd
        $alreadyRun = DaoDistribution::where('week_end', '>=', $weekEnd->copy()->subMinutes(15))
            ->where('week_end', '<=', $weekEnd->copy()->addMinutes(15))
            ->exists();

        if ($alreadyRun) {
            Log::info("Weekly DAO Distribution already executed for period ending {$weekEnd}");
            return;
        }

        // Calculate Turnover from active USDT topups within this period
        $totalWeeklyBusiness = (float) StackingDeposite::where('status', 1)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->sum('usdt');

        Log::info("Weekly Global Turnover for DAO Pools ({$weekStart->toDateTimeString()} to {$weekEnd->toDateTimeString()}, {$periodDays} days): \${$totalWeeklyBusiness} USDT");

        $profileStore = ProfileStore::where('id', 1)->first();
        $tokenPrice = ($profileStore && (float)$profileStore->price > 0) ? (float)$profileStore->price : 1.0;

        $tiers = DaoDetail::where('status', 1)->orderBy('dao_type', 'desc')->get();
        $deadline60Days = $poolStartDate->copy()->addDays(60); // 2027-01-04 23:59:59

        foreach ($tiers as $tier) {
            $poolAmount = ($totalWeeklyBusiness * (float)$tier->pool_percent) / 100.0;

            // Fetch active members who hold this DAO tier
            $candidates = UserDetails::where('is_dao', $tier->dao_type)
                ->where('userstatus', 1)
                ->get();

            $eligibleUserIds = [];

            foreach ($candidates as $user) {
                // Check & Sync Rank live
                RankIncomeController::checkAndSyncUserRank($user->id);
                $user = $user->fresh();

                // 60-Day Rank Qualification Rule:
                // Counter starts from 5 Nov 2026.
                // If past 60 days and member hasn't achieved required rank, they stop getting the 4%/3%/2.5% pool.
                $isPast60Days = Carbon::now()->gt($deadline60Days);
                $hasRequiredRank = ((int)$user->rank_level >= (int)$tier->rank_required);

                if ($isPast60Days && !$hasRequiredRank) {
                    Log::info("DAO Member {$user->id} skipped in {$tier->dao_name}: 60 days expired and Rank V{$user->rank_level} does not meet V{$tier->rank_required}. DAO Pool dividend paused.");
                    continue;
                }

                // Capping Check: Max Cap = Package * Multiplier
                $maxCap = (float)$tier->package_amount * (float)$tier->capping_multiplier;
                $totalReceived = (float) DaoIncome::where('userid', $user->id)
                    ->where('dao_type', $tier->dao_type)
                    ->sum('amt_usdt');

                if ($totalReceived >= $maxCap) {
                    Log::info("DAO Member {$user->id} skipped in {$tier->dao_name}: Capping limit \${$maxCap} reached.");
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
                'period_days'           => $periodDays,
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
