<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StackingDetailController;
use App\RankDetail;
use App\RankIncome;
use App\UserDetails;
use App\ProfileStore;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class RankIncomeController extends Controller
{
    /**
     * Helper: Check and Sync user's rank into user_details table.
     * Evaluates Power Leg & Weaker Leg against rank_details (V1 to V8).
     */
    public static function checkAndSyncUserRank($userId)
    {
        $userDetail = UserDetails::where('id', $userId)->first();
        if (!$userDetail) return null;

        $allRanks = RankDetail::where('status', 1)->orderBy('rank_level', 'desc')->get();
        if ($allRanks->isEmpty()) return null;

        $legs = $userDetail->getLegBusiness();
        $power = (float)($legs['power'] ?? 0);
        $weaker = (float)($legs['weaker'] ?? 0);

        $highestRank = null;
        foreach ($allRanks as $rank) {
            if ($power >= (float)$rank->power_leg && $weaker >= (float)$rank->weaker_leg) {
                $highestRank = $rank;
                break;
            }
        }

        $newRankLevel = $highestRank ? (int)$highestRank->rank_level : 0;
        $newRankName = $highestRank ? $highestRank->rank_name : 'None';

        if ($userDetail->rank_level != $newRankLevel || $userDetail->rank_name != $newRankName) {
            UserDetails::where('id', $userId)->update([
                'rank_level' => $newRankLevel,
                'rank_name' => $newRankName,
            ]);
            Log::info("Rank Updated for User {$userDetail->userid}: {$newRankName} (Level {$newRankLevel})");
        }

        return $highestRank;
    }

    /**
     * Daily Cron: Checks and updates rank qualifications in user_details for all active users.
     */
    public function dailyRankQualificationCheck()
    {
        Log::info('--- Starting Daily Rank Qualification Check ---');

        $activeUsers = UserDetails::where('userstatus', 1)->get();
        $allRanks = RankDetail::where('status', 1)->orderBy('rank_level', 'desc')->get();
        $updatedCount = 0;

        foreach ($activeUsers as $user) {
            $legs = $user->getLegBusiness();
            $power = (float)($legs['power'] ?? 0);
            $weaker = (float)($legs['weaker'] ?? 0);

            $highestRank = null;
            foreach ($allRanks as $rank) {
                if ($power >= (float)$rank->power_leg && $weaker >= (float)$rank->weaker_leg) {
                    $highestRank = $rank;
                    break;
                }
            }

            $newRankLevel = $highestRank ? (int)$highestRank->rank_level : 0;
            $newRankName = $highestRank ? $highestRank->rank_name : 'None';

            if ($user->rank_level != $newRankLevel || $user->rank_name != $newRankName) {
                UserDetails::where('id', $user->id)->update([
                    'rank_level' => $newRankLevel,
                    'rank_name' => $newRankName,
                ]);
                $updatedCount++;
            }
        }

        Log::info("--- Daily Rank Qualification Check Complete: {$updatedCount} users updated ---");
    }

    /**
     * Weekly Rank Income Distribution Cron (V1 to V8)
     * Rules:
     * - Non-cumulative: Only the highest active eligible rank reward applies.
     * - Power Leg (PL) & Weaker Leg (WL) validation.
     * - Capping deduction and token price conversion.
     */
    public function weeklyRankDistribution()
    {
        Log::info('--- Starting Weekly Rank Income Distribution (V1 to V8) ---');

        // First run qualification check to ensure up-to-date ranks in user_details
        $this->dailyRankQualificationCheck();

        $allRanks = RankDetail::where('status', 1)->get()->keyBy('rank_level');
        if ($allRanks->isEmpty()) {
            Log::warning('Rank details configuration not found or inactive');
            return;
        }

        $distDate = Carbon::now()->toDateString();

        // Check if already distributed today
        $alreadyRun = RankIncome::where('distributed_date', $distDate)->exists();
        if ($alreadyRun) {
            Log::info("Rank Income already distributed for date: {$distDate}");
            return;
        }

        $profileStore = ProfileStore::where('id', 1)->first();
        $tokenPrice = $profileStore && $profileStore->price > 0 ? (float)$profileStore->price : 1.0;
        $cappingController = new StackingDetailController();

        $eligibleUsers = UserDetails::where('userstatus', 1)
            ->where('rank_level', '>', 0)
            ->where('capping', '!=', 1)
            ->get();

        $distributedCount = 0;
        $totalDistributedUsdt = 0;

        foreach ($eligibleUsers as $user) {
            if (!$user->user() || $user->user()->permission != 1) {
                continue;
            }

            $rankLevel = (int)$user->rank_level;
            if (!isset($allRanks[$rankLevel])) continue;

            $rank = $allRanks[$rankLevel];
            $rewardUsdt = (float)$rank->weekly_reward;

            if ($rewardUsdt > 0) {
                // Capping calculation: Deducts commission from user's active deposits' remaining capping
                $finalUsdt = $cappingController->cappingCalculation($user->id, $rewardUsdt);

                if ($finalUsdt > 0) {
                    $tokenAmount = $finalUsdt / $tokenPrice;
                    $legs = $user->getLegBusiness();
                    $power = (float)($legs['power'] ?? 0);
                    $weaker = (float)($legs['weaker'] ?? 0);

                    RankIncome::create([
                        'userid' => $user->id,
                        'rank_id' => $rank->id,
                        'rank_name' => $rank->rank_name,
                        'amount' => $tokenAmount,
                        'remaining' => $tokenAmount,
                        'amt_usdt' => $finalUsdt,
                        'remaining_usdt' => $finalUsdt,
                        'power_leg_business' => $power,
                        'weaker_leg_business' => $weaker,
                        'distributed_date' => $distDate,
                        'status' => 0,
                        'created_at' => now(),
                    ]);

                    $distributedCount++;
                    $totalDistributedUsdt += $finalUsdt;

                    Log::info("Weekly Rank Income: User {$user->userid} awarded {$rank->rank_name} (\${$finalUsdt} USDT)");
                }
            }
        }

        Log::info("--- Weekly Rank Distribution Finished: {$distributedCount} users paid, Total \${$totalDistributedUsdt} USDT ---");
    }

    /**
     * User Rank Income Page
     */
    public function userRankIncome(Request $request)
    {
        $userId = Session::get('user.id');
        $userDetail = UserDetails::where('id', $userId)->first();
        if (!$userDetail) {
            return redirect('/login');
        }

        // Live check and sync rank
        self::checkAndSyncUserRank($userId);
        $userDetail = $userDetail->fresh();

        $rankQualification = $userDetail->getRankQualification();

        $query = RankIncome::where('userid', $userId);

        if ($request->filled('fromdate') && $request->filled('todate')) {
            $from = $request->fromdate;
            $to = $request->todate . ' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        $history = $query->orderBy('id', 'desc')->get();
        $totalEarnedUsdt = RankIncome::where('userid', $userId)->sum('amt_usdt');
        $totalEarnedToken = RankIncome::where('userid', $userId)->sum('amount');

        return view('user.incomerank', [
            'user' => $userDetail,
            'rankQualification' => $rankQualification,
            'history' => $history,
            'totalEarnedUsdt' => $totalEarnedUsdt,
            'totalEarnedToken' => $totalEarnedToken,
        ]);
    }
}
