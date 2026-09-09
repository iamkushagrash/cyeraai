<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StackingDetailController;
use App\PoolDetail;
use App\PoolDistribution;
use App\PoolIncome;
use App\UserDetails;
use App\StackingDeposite;
use App\ProfileStore;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PoolIncomeController extends Controller
{
    /**
     * 1. Daily Pool Distribution (1.5% Global Turnover)
     * Period: Yesterday (00:00:00 - 23:59:59)
     * Criteria: Self Active Investment >= $100
     */
    public function dailyPoolDistribution()
    {
        Log::info('--- Starting Daily Pool Distribution (1.5%) ---');
        $pool = PoolDetail::where('pool_type', 1)->where('status', 1)->first();
        if (!$pool) {
            Log::warning('Daily Pool config not found or inactive');
            return;
        }

        $periodStart = Carbon::yesterday()->startOfDay();
        $periodEnd = Carbon::yesterday()->endOfDay();
        $distDate = Carbon::yesterday()->toDateString();

        // Check if already distributed for this date
        $alreadyRun = PoolDistribution::where('pool_type', 1)
            ->where('distributed_date', $distDate)
            ->exists();
        if ($alreadyRun) {
            Log::info("Daily Pool already distributed for {$distDate}");
            return;
        }

        // 1. Get all deposits created in yesterday's period
        $depositsInPeriod = StackingDeposite::where('status', 1)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->get();

        $totalTurnover = (float)$depositsInPeriod->sum('usdt');
        $poolAmount = ($totalTurnover * (float)$pool->pool_percent) / 100;

        // 2. Find Sponsors of the users who staked in this period
        $eligibleUserMap = [];
        foreach ($depositsInPeriod as $dep) {
            $stakedUser = UserDetails::where('id', $dep->userid)->orWhere('userid', $dep->userid)->first();
            if ($stakedUser && $stakedUser->sponsorid > 0) {
                $sponsor = UserDetails::where('id', $stakedUser->sponsorid)->orWhere('userid', $stakedUser->sponsorid)->first();
                if ($sponsor) {
                    // Check Sponsor's own qualification: Active user, not capped out, self investment >= min_self_investment ($100)
                    if ($sponsor->userstatus == 1 && $sponsor->capping != 1 && (float)$sponsor->current_self_investment >= (float)$pool->min_self_investment) {
                        $eligibleUserMap[$sponsor->id] = $sponsor;
                    }
                }
            }
        }

        $eligibleUsers = array_values($eligibleUserMap);
        $eligibleCount = count($eligibleUsers);
        $perUserShare = ($eligibleCount > 0 && $poolAmount > 0) ? ($poolAmount / $eligibleCount) : 0;

        $distribution = PoolDistribution::create([
            'pool_id'              => $pool->id,
            'pool_type'            => 1,
            'period_start'         => $periodStart,
            'period_end'           => $periodEnd,
            'total_turnover'       => $totalTurnover,
            'pool_percent'         => $pool->pool_percent,
            'total_pool_amount'    => $poolAmount,
            'eligible_users_count' => $eligibleCount,
            'per_user_share'       => $perUserShare,
            'distributed_date'     => $distDate,
            'status'               => 1,
        ]);

        if ($perUserShare > 0) {
            $price = ProfileStore::where('id', 1)->first()->price ?? 1;
            $capping = new StackingDetailController();

            foreach ($eligibleUsers as $user) {
                $finalUsdt = $capping->cappingCalculation($user->id, $perUserShare);
                if ($finalUsdt > 0) {
                    $tokenAmt = $finalUsdt / $price;
                    PoolIncome::create([
                        'distribution_id' => $distribution->id,
                        'userid'          => $user->id,
                        'pool_id'         => $pool->id,
                        'pool_type'       => 1,
                        'amount'          => $tokenAmt,
                        'remaining'       => $tokenAmt,
                        'amt_usdt'        => $finalUsdt,
                        'remaining_usdt'  => $finalUsdt,
                        'status'          => 0,
                    ]);
                }
            }
        }

        Log::info("Daily Pool completed: Turnover=\${$totalTurnover}, Pool=\${$poolAmount}, Users={$eligibleCount}, Share=\${$perUserShare}");
    }

    /**
     * 2. Weekly Pool Distribution (1.5% Global Turnover)
     * Period: Previous Full Week (Monday 00:00:00 - Sunday 23:59:59)
     * Criteria: Self Active Investment >= $100 + 5 Directs (>= $100 each)
     */
    public function weeklyPoolDistribution()
    {
        Log::info('--- Starting Weekly Pool Distribution (1.5%) ---');
        $pool = PoolDetail::where('pool_type', 2)->where('status', 1)->first();
        if (!$pool) {
            Log::warning('Weekly Pool config not found or inactive');
            return;
        }

        $periodStart = Carbon::now()->subWeek()->startOfWeek();
        $periodEnd = Carbon::now()->subWeek()->endOfWeek();
        $distDate = Carbon::now()->toDateString();

        // Check if already distributed for this week
        $alreadyRun = PoolDistribution::where('pool_type', 2)
            ->whereBetween('period_start', [$periodStart->copy()->subMinutes(5), $periodStart->copy()->addMinutes(5)])
            ->exists();
        if ($alreadyRun) {
            Log::info("Weekly Pool already distributed for week starting {$periodStart}");
            return;
        }

        $totalTurnover = (float)StackingDeposite::where('status', 1)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->sum('usdt');

        $poolAmount = ($totalTurnover * (float)$pool->pool_percent) / 100;

        // Check qualification for all active users
        $activeUsers = UserDetails::where('userstatus', 1)
            ->where('capping', '!=', 1)
            ->where('current_self_investment', '>=', (float)$pool->min_self_investment)
            ->get();

        $eligibleUsers = [];
        foreach ($activeUsers as $user) {
            $quals = $user->getPoolQualifications();
            if ($quals['weekly']['is_qualified']) {
                $eligibleUsers[] = $user;
            }
        }

        $eligibleCount = count($eligibleUsers);
        $perUserShare = ($eligibleCount > 0 && $poolAmount > 0) ? ($poolAmount / $eligibleCount) : 0;

        $distribution = PoolDistribution::create([
            'pool_id'              => $pool->id,
            'pool_type'            => 2,
            'period_start'         => $periodStart,
            'period_end'           => $periodEnd,
            'total_turnover'       => $totalTurnover,
            'pool_percent'         => $pool->pool_percent,
            'total_pool_amount'    => $poolAmount,
            'eligible_users_count' => $eligibleCount,
            'per_user_share'       => $perUserShare,
            'distributed_date'     => $distDate,
            'status'               => 1,
        ]);

        if ($perUserShare > 0) {
            $price = ProfileStore::where('id', 1)->first()->price ?? 1;
            $capping = new StackingDetailController();

            foreach ($eligibleUsers as $user) {
                $finalUsdt = $capping->cappingCalculation($user->id, $perUserShare);
                if ($finalUsdt > 0) {
                    $tokenAmt = $finalUsdt / $price;
                    PoolIncome::create([
                        'distribution_id' => $distribution->id,
                        'userid'          => $user->id,
                        'pool_id'         => $pool->id,
                        'pool_type'       => 2,
                        'amount'          => $tokenAmt,
                        'remaining'       => $tokenAmt,
                        'amt_usdt'        => $finalUsdt,
                        'remaining_usdt'  => $finalUsdt,
                        'status'          => 0,
                    ]);
                }
            }
        }

        Log::info("Weekly Pool completed: Turnover=\${$totalTurnover}, Pool=\${$poolAmount}, Users={$eligibleCount}, Share=\${$perUserShare}");
    }

    /**
     * 3. Monthly Pool Distribution (2.0% Global Turnover)
     * Period: Full Previous Month (1st 00:00:00 - Last Day 23:59:59)
     * Criteria: Self Active Investment >= $100 + 15 Directs (>= $100 each) + Power Leg >= $5,000 & Weaker Leg >= $5,000
     */
    public function monthlyPoolDistribution()
    {
        Log::info('--- Starting Monthly Pool Distribution (2.0%) ---');
        $pool = PoolDetail::where('pool_type', 3)->where('status', 1)->first();
        if (!$pool) {
            Log::warning('Monthly Pool config not found or inactive');
            return;
        }

        $periodStart = Carbon::now()->subMonth()->startOfMonth();
        $periodEnd = Carbon::now()->subMonth()->endOfMonth();
        $distDate = Carbon::now()->toDateString();

        // Check if already distributed for this month
        $alreadyRun = PoolDistribution::where('pool_type', 3)
            ->whereBetween('period_start', [$periodStart->copy()->subMinutes(5), $periodStart->copy()->addMinutes(5)])
            ->exists();
        if ($alreadyRun) {
            Log::info("Monthly Pool already distributed for month starting {$periodStart}");
            return;
        }

        $totalTurnover = (float)StackingDeposite::where('status', 1)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->sum('usdt');

        $poolAmount = ($totalTurnover * (float)$pool->pool_percent) / 100;

        // Check qualification for all active users
        $activeUsers = UserDetails::where('userstatus', 1)
            ->where('capping', '!=', 1)
            ->where('current_self_investment', '>=', (float)$pool->min_self_investment)
            ->get();

        $eligibleUsers = [];
        foreach ($activeUsers as $user) {
            $quals = $user->getPoolQualifications();
            if ($quals['monthly']['is_qualified']) {
                $eligibleUsers[] = $user;
            }
        }

        $eligibleCount = count($eligibleUsers);
        $perUserShare = ($eligibleCount > 0 && $poolAmount > 0) ? ($poolAmount / $eligibleCount) : 0;

        $distribution = PoolDistribution::create([
            'pool_id'              => $pool->id,
            'pool_type'            => 3,
            'period_start'         => $periodStart,
            'period_end'           => $periodEnd,
            'total_turnover'       => $totalTurnover,
            'pool_percent'         => $pool->pool_percent,
            'total_pool_amount'    => $poolAmount,
            'eligible_users_count' => $eligibleCount,
            'per_user_share'       => $perUserShare,
            'distributed_date'     => $distDate,
            'status'               => 1,
        ]);

        if ($perUserShare > 0) {
            $price = ProfileStore::where('id', 1)->first()->price ?? 1;
            $capping = new StackingDetailController();

            foreach ($eligibleUsers as $user) {
                $finalUsdt = $capping->cappingCalculation($user->id, $perUserShare);
                if ($finalUsdt > 0) {
                    $tokenAmt = $finalUsdt / $price;
                    PoolIncome::create([
                        'distribution_id' => $distribution->id,
                        'userid'          => $user->id,
                        'pool_id'         => $pool->id,
                        'pool_type'       => 3,
                        'amount'          => $tokenAmt,
                        'remaining'       => $tokenAmt,
                        'amt_usdt'        => $finalUsdt,
                        'remaining_usdt'  => $finalUsdt,
                        'status'          => 0,
                    ]);
                }
            }
        }

        Log::info("Monthly Pool completed: Turnover=\${$totalTurnover}, Pool=\${$poolAmount}, Users={$eligibleCount}, Share=\${$perUserShare}");
    }

    /**
     * User Panel: Pool Income Ledger & Live Qualification Dashboard
     */
    public function showPoolIncomePage(Request $request)
    {
        $userId = Session::get('user.id');
        $userDetail = UserDetails::where('id', $userId)->first();
        if (!$userDetail) {
            return redirect('/login');
        }

        $poolQualifications = $userDetail->getPoolQualifications();

        // Summary Totals
        $dailyTotalUsdt = (float)PoolIncome::where('userid', $userId)->where('pool_type', 1)->sum('amt_usdt');
        $weeklyTotalUsdt = (float)PoolIncome::where('userid', $userId)->where('pool_type', 2)->sum('amt_usdt');
        $monthlyTotalUsdt = (float)PoolIncome::where('userid', $userId)->where('pool_type', 3)->sum('amt_usdt');
        $grandTotalUsdt = $dailyTotalUsdt + $weeklyTotalUsdt + $monthlyTotalUsdt;

        $dailyTotalTokens = (float)PoolIncome::where('userid', $userId)->where('pool_type', 1)->sum('amount');
        $weeklyTotalTokens = (float)PoolIncome::where('userid', $userId)->where('pool_type', 2)->sum('amount');
        $monthlyTotalTokens = (float)PoolIncome::where('userid', $userId)->where('pool_type', 3)->sum('amount');
        $grandTotalTokens = $dailyTotalTokens + $weeklyTotalTokens + $monthlyTotalTokens;

        // User Pool Incomes with distribution details
        $incomes = PoolIncome::with(['poolDetail', 'distribution'])
            ->where('userid', $userId)
            ->orderBy('id', 'desc')
            ->get();

        // Recent Global Pool Distributions
        $recentDistributions = PoolDistribution::with('poolDetail')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return view('user.incomepool', compact(
            'userDetail',
            'poolQualifications',
            'dailyTotalUsdt',
            'weeklyTotalUsdt',
            'monthlyTotalUsdt',
            'grandTotalUsdt',
            'dailyTotalTokens',
            'weeklyTotalTokens',
            'monthlyTotalTokens',
            'grandTotalTokens',
            'incomes',
            'recentDistributions'
        ));
    }
}
