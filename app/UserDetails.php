<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class UserDetails extends Model
{
    public $timestamps = false;

    protected $fillable = ['userid', 'sponserid', 'level', 'total_direct', 'active_direct', 'total_downline', 'active_downline', 'level_income', 'roi_income', 'wallet_amount', 'total_investment', 'current_investment', 'total_level_investment', 'current_level_investment', 'total_self_investment', 'current_self_investment', 'total_direct_investment', 'current_direct_investment', 'level_status', 'capping', 'roi_status', 'updated_at', 'userstatus', 'userstate', 'rank_level', 'rank_name', 'booster', 'loan_attempts', 'power_protected', 'lifetime_protected', 'silver_protected'];


    public function stackingDeposite()
    {
        return $this->hasMany('\App\StackingDeposite', 'userid', 'id');
    }

    public function stackingIncome()
    {
        return $this->hasMany('\App\CpsIncome', 'userid', 'id')->get();
    }

    public function levelIncome()
    {
        return $this->hasMany('\App\LevelIncome', 'userid', 'id')->get();
    }

    public function bonusReward()
    {
        return $this->hasMany('\App\BonusReward', 'userid', 'id')->get();
    }

    public function clubIncome()
    {
        return $this->hasMany('\App\ClubIncome', 'userid', 'id')->get();
    }

    public function totalIncome()
    {
        return $this->stackingIncome()->sum('amount') + $this->levelIncome()->sum('amount') + $this->bonusReward()->sum('amount') + $this->clubIncome()->sum('amount');
    }

    public function totalIncomeUSDT()
    {
        return $this->stackingIncome()->sum('amt_usdt') + $this->levelIncome()->sum('amt_usdt') + $this->bonusReward()->sum('amt_usdt') + $this->clubIncome()->sum('amt_usdt');
    }

    public function remainingIncome()
    {
        return $this->stackingIncome()->where('status', 0)->sum('remaining_usdt') + $this->levelIncome()->where('status', 0)->sum('remaining_usdt') + $this->bonusReward()->where('status', '!=', 3)->sum('remaining_usdt');
    }

    /*public function remainingIncome(){
        return $this->stackingIncome()->where('status',0)->sum('remaining_usdt')+$this->levelIncome()->where('status',0)->sum('remaining_usdt')+$this->bonusReward()->where('status','!=',3)->sum('remaining_usdt')+$this->clubIncome()->where('status',0)->sum('remaining_usdt');
    }*/

    public function lockedIncome()
    {
        return $this->stackingIncome()->where('status', 3)->sum('remaining_usdt') + $this->levelIncome()->where('status', 3)->sum('remaining_usdt') + $this->bonusReward()->where('status', 3)->sum('remaining_usdt') + $this->clubIncome()->where('status', 3)->sum('remaining_usdt');
    }

    public function lifetimeIncome()
    {
        return $this->hasMany('\App\AchievementIncome', 'userid', 'id')->get();
    }

    public function assetDetail()
    {
        return $this->hasOne('\App\AssetDetail', 'userid', 'id')->first();
    }

    public function user()
    {
        return $this->hasOne('\App\User', 'id', 'userid')->first();
    }

    public function totalDirect()
    {
        return $this->hasMany('\App\UserDetails', 'sponsorid', 'userid');
    }

    public function totalWithdraw()
    {
        return $this->hasMany('\App\TransactionDetail', 'userid', 'id')->where([['txntype', 1], ['txndesc', 'Withdrawal'], ['paymentstatus', 2]])->get();
    }

    public function guiderDetails()
    {
        return $this->hasOne('\App\UserDetails', 'userid', 'sponsorid');
    }

    public function directDetails()
    {
        return $this->totalDirect()->join('users', 'user_details.userid', '=', 'users.id')
            ->selectRaw('concat(email,"( ",usersname," )") as name,case when userstatus=1 then "circleGreen" when userstatus=0 then "circleRed" end as class,userid,sponsorid')
            ->get();
    }

    public function accountDeposite()
    {
        return $this->hasMany('\App\AccountDeposite', 'userid', 'id')->get();
    }

    public function userLoanStatus()
    {
        return $this->hasOne('\App\LoanDetails', 'userid', 'id')->first();
    }

    public function boosterCheck()
    {
        return ($this->booster >= 2) ? true : false;
    }

    public function levelStatus()
    {
        $allLevels = \App\LevelDetails::where('status', 1)->orderBy('open_level', 'desc')->get();
        $teamBiz = (float) ($this->total_level_investment + $this->total_direct_investment);
        $activeDirects = (int) ($this->active_direct);

        foreach ($allLevels as $lvl) {
            if ($activeDirects >= $lvl->direct_count && $teamBiz >= (float) $lvl->min_amount) {
                return (float) $lvl->cps;
            }
        }
        return 0;
    }

    public function getMaxUnlockedLevel()
    {
        $allLevels = \App\LevelDetails::where('status', 1)->orderBy('open_level', 'asc')->get();
        $teamBiz = (float) ($this->total_level_investment + $this->total_direct_investment);
        $activeDirects = (int) ($this->active_direct);
        $maxLvl = 1; // Level 1 is direct, always unlocked

        foreach ($allLevels as $lvl) {
            if ($activeDirects >= $lvl->direct_count && $teamBiz >= (float) $lvl->min_amount) {
                $maxLvl = max($maxLvl, $lvl->open_level);
            }
        }
        return $maxLvl;
    }

    public function getLegBusiness()
    {
        $directs = \App\UserDetails::where('sponsorid', $this->userid)->orderBy('total_investment', 'desc')->get();
        if ($directs->count() > 0) {
            $power = (float) ($directs->first()->total_investment);
            $weaker = (float) ($directs->sum('total_investment') - $power);
        } else {
            $power = 0;
            $weaker = 0;
        }
        $total = $power + $weaker;
        return [
            'power' => $power,
            'weaker' => $weaker,
            'total' => $total,
            'power_leg' => $power,
            'weaker_leg' => $weaker,
            'total_leg_business' => $total
        ];
    }

    public function getBoosterStats()
    {
        $firstDeposit = \App\StackingDeposite::where('userid', $this->id)
            ->where('staketype', 1)
            ->orderBy('created_at', 'asc')
            ->first();

        // If deposited, use first deposit activation; else use registration date
        $activationDate = $firstDeposit ? \Carbon\Carbon::parse($firstDeposit->created_at) : \Carbon\Carbon::parse($this->created_at ?? now());
        $expiryDate = $activationDate->copy()->addDays(7);
        $now = \Carbon\Carbon::now();
        $isExpired = $now->greaterThan($expiryDate);
        $remainingSeconds = $isExpired ? 0 : $now->diffInSeconds($expiryDate);
        $isWindowActive = !$isExpired;

        $days = floor($remainingSeconds / 86400);
        $hours = floor(($remainingSeconds % 86400) / 3600);
        $mins = floor(($remainingSeconds % 3600) / 60);
        $secs = $remainingSeconds % 60;
        $timeLeftHuman = $isWindowActive
            ? sprintf("%02dd : %02dh : %02dm : %02ds", $days, $hours, $mins, $secs)
            : 'Window Expired';

        // Direct referrals sponsored by user who activated a package >= 100 in the 7-day window
        $directs7DaysCount = \App\UserDetails::where('user_details.sponsorid', $this->userid)
            ->join('stacking_deposites as sd', 'sd.userid', '=', 'user_details.id')
            ->where('sd.usdt', '>=', 100)
            ->whereBetween('sd.created_at', [$activationDate, $expiryDate])
            ->distinct('user_details.id')
            ->count('user_details.id');

        // Booster 1: 5 Directs ($100+) in 7 Days -> 1.0% Daily ROI
        // Booster 2: 15 Directs ($100+) in 7 Days -> 1.5% Daily ROI
        $boosterTier = (int) ($this->booster);
        $booster1Active = ($boosterTier === 2 || $directs7DaysCount >= 5);
        $booster2Active = ($boosterTier === 3 || $directs7DaysCount >= 15);

        // Effective active tier
        $activeTier = 1;
        $dailyRoiPct = 0.50;
        if ($booster2Active) {
            $activeTier = 3;
            $dailyRoiPct = 1.50;
        } elseif ($booster1Active) {
            $activeTier = 2;
            $dailyRoiPct = 1.00;
        }

        return [
            'activation_date' => $activationDate->format('Y-m-d H:i:s'),
            'expiry_date' => $expiryDate->format('Y-m-d H:i:s'),
            'expiry_timestamp' => $expiryDate->timestamp * 1000,
            'is_expired' => $isExpired,
            'is_window_active' => $isWindowActive,
            'remaining_seconds' => $remainingSeconds,
            'time_left_human' => $timeLeftHuman,
            'directs_7days' => $directs7DaysCount,
            'booster1_active' => $booster1Active,
            'booster1_needed' => max(0, 5 - $directs7DaysCount),
            'booster2_active' => $booster2Active,
            'booster2_needed' => max(0, 15 - $directs7DaysCount),
            'active_tier' => $activeTier,
            'daily_roi_pct' => $dailyRoiPct,
            'current_rate' => $dailyRoiPct,
            'booster1' => [
                'req_directs' => 5,
                'min_amount' => 100,
                'qualified_directs' => $directs7DaysCount,
                'is_active' => $booster1Active,
                'progress_pct' => min(100, round(($directs7DaysCount / 5) * 100)),
                'daily_rate' => '1.0%'
            ],
            'booster2' => [
                'req_directs' => 15,
                'min_amount' => 100,
                'qualified_directs' => $directs7DaysCount,
                'is_active' => $booster2Active,
                'progress_pct' => min(100, round(($directs7DaysCount / 15) * 100)),
                'daily_rate' => '1.5%'
            ]
        ];
    }

    public function getCappingTier()
    {
        $selfInv = (float) ($this->current_self_investment);
        $legs = $this->getLegBusiness();
        $power = $legs['power'];
        $weaker = $legs['weaker'];

        // Directs count with >= $100
        $directs100 = \App\UserDetails::where('user_details.sponsorid', $this->userid)
            ->join('stacking_deposites as sd', 'sd.userid', '=', 'user_details.id')
            ->where('sd.usdt', '>=', 100)
            ->distinct('user_details.id')
            ->count('user_details.id');

        // Directs count with >= $200
        $directs200 = \App\UserDetails::where('user_details.sponsorid', $this->userid)
            ->join('stacking_deposites as sd', 'sd.userid', '=', 'user_details.id')
            ->where('sd.usdt', '>=', 200)
            ->distinct('user_details.id')
            ->count('user_details.id');

        $multiplier = 2; // Default 2X
        $nextTier = '3X';
        $nextReqs = [
            'self_deposit' => 200,
            'directs_count' => 5,
            'direct_min_amount' => 100,
            'power_leg_turnover' => 5000,
            'weaker_leg_turnover' => 5000
        ];

        // Check 10X: Self >= $1000 + 15 Directs (200+) + Power Leg >= 50K + Weaker Leg >= 50K
        if ($selfInv >= 1000 && $directs200 >= 15 && $power >= 50000 && $weaker >= 50000) {
            $multiplier = 10;
            $nextTier = null;
            $nextReqs = null;
        }
        // Check 5X: Self >= $500 + 15 Directs (100+) + Power Leg >= 25K + Weaker Leg >= 25K
        elseif ($selfInv >= 500 && $directs100 >= 15 && $power >= 25000 && $weaker >= 25000) {
            $multiplier = 5;
            $nextTier = '10X';
            $nextReqs = [
                'self_deposit' => 1000,
                'directs_count' => 15,
                'direct_min_amount' => 200,
                'power_leg_turnover' => 50000,
                'weaker_leg_turnover' => 50000
            ];
        }
        // Check 3X: Self >= $200 + 5 Directs (100+) + Power Leg >= 5K + Weaker Leg >= 5K
        elseif ($selfInv >= 200 && $directs100 >= 5 && $power >= 5000 && $weaker >= 5000) {
            $multiplier = 3;
            $nextTier = '5X';
            $nextReqs = [
                'self_deposit' => 500,
                'directs_count' => 15,
                'direct_min_amount' => 100,
                'power_leg_turnover' => 25000,
                'weaker_leg_turnover' => 25000
            ];
        }

        return [
            'multiplier' => $multiplier,
            'active_tier' => $multiplier . 'X',
            'self_inv' => $selfInv,
            'directs_100' => $directs100,
            'directs_200' => $directs200,
            'power_leg' => $power,
            'weaker_leg' => $weaker,
            'next_tier' => $nextTier,
            'next_requirements' => $nextReqs,
            'qual_3x' => ($selfInv >= 200 && $directs100 >= 5 && $power >= 5000 && $weaker >= 5000),
            'qual_5x' => ($selfInv >= 500 && $directs100 >= 15 && $power >= 25000 && $weaker >= 25000),
            'qual_10x' => ($selfInv >= 1000 && $directs200 >= 15 && $power >= 50000 && $weaker >= 50000),
        ];
    }

    public function clubBusiness()
    {
        $direct = \App\UserDetails::where('sponsorid', $this->userid)->orderBy('total_investment', 'desc')->get();

        $filterLevel = null;
        $nextLevel = null;
        if (sizeof($direct) > 0) {
            if ($this->power_protected) {
                $power = 1;
                $first = 5000000;//
                $rest = (($direct->sum('total_investment')));
                $achievedLevel = \App\ClubDetails::where([['business_min', '<=', ($first + $rest)]])->orderBy('cps_amount')->get();
                $filterLevel = $achievedLevel->filter(function ($q) use ($rest) {
                    return ($q->business_min * 60 / 100) <= $rest;
                });
                if (!is_null($filterLevel->last())) {
                    $nextLevel = \App\ClubDetails::where([['cps_amount', '>', $filterLevel->last()->cps_amount]])->orderBy('cps_amount')->first();
                } else {
                    $nextLevel = \App\ClubDetails::where([['cps_amount', '>', 0]])->orderBy('cps_amount')->first();
                }

            } else {
                $power = 0;
                $first = (float) ($direct->first()->total_investment);
                $rest = (($direct->sum('total_investment')) - $first);
                $achievedLevel = \App\ClubDetails::where([['business_min', '<=', ($direct->sum('total_investment'))]])->orderBy('cps_amount')->get();
                $filterLevel = $achievedLevel->filter(function ($q) use ($rest, $first) {
                    return ($q->business_min * 40 / 100) <= $first && ($q->business_min * 60 / 100) <= $rest;
                });
                if (!is_null($filterLevel->last())) {
                    $nextLevel = \App\ClubDetails::where([['cps_amount', '>', $filterLevel->last()->cps_amount]])->orderBy('cps_amount')->first();
                } else {
                    $nextLevel = \App\ClubDetails::where([['cps_amount', '>', 0]])->orderBy('cps_amount')->first();
                }

            }
        } else {
            $power = 0;
            $first = 0;
            $rest = 0;
            $nextLevel = \App\ClubDetails::where([['cps_amount', '>', 0]])->orderBy('cps_amount')->first();
        }
        return array('first' => $first, 'rest' => $rest, 'power' => $power, 'achieved' => ((!is_null($filterLevel)) ? $filterLevel->last() : $filterLevel), 'next' => $nextLevel);
    }

    public function lifetimeAchievementBusiness()
    {
        $d = \App\UserDetails::where('sponsorid', $this->userid)->orderBy('total_investment', 'desc')->get();
        $direct = $d->all();
        $arr = [];
        $filteredLevel = null;
        $nextLevel = null;
        if (sizeof($direct) > 1) {
            if ($this->lifetime_protected) {
                $power = 1;
                $first = 80000000;
                $second = (float) array_shift($direct)['total_investment'];
                $rest = (($d->sum('total_investment')) - ($second));//dd($arr);
                $achievedLevel = \App\AchievementDetail::where('business_min', '<=', ($first + $d->sum('total_investment')))->orderBy('cps_amount')->get();
                $filteredLevel = $achievedLevel->filter(function ($q) use ($second, $rest) {
                    return ($q->business_min * $q->secondline / 100) <= $second && ($q->business_min * $q->remainingline / 100) <= $rest;
                });
                if (!is_null($filteredLevel->last()))
                    $nextLevel = \App\AchievementDetail::where('cps_amount', '>', $filteredLevel->last()->cps_amount)->orderBy('cps_amount')->first();
                else
                    $nextLevel = \App\AchievementDetail::where('cps_amount', '>', 0)->orderBy('cps_amount')->first();
            } else {
                $power = 0;
                $first = (float) array_shift($direct)['total_investment'];//dd($first,$d->first()->total_investment);
                $second = (float) array_shift($direct)['total_investment'];
                $rest = (($d->sum('total_investment')) - ($first + $second));//dd($arr);
                $achievedLevel = \App\AchievementDetail::where('business_min', '<=', ($d->sum('total_investment')))->orderBy('cps_amount')->get();
                $filteredLevel = $achievedLevel->filter(function ($q) use ($first, $second, $rest) {
                    return ($q->business_min * $q->firstline / 100) <= $first && ($q->business_min * $q->secondline / 100) <= $second && ($q->business_min * $q->remainingline / 100) <= $rest;
                });
                if (!is_null($filteredLevel->last()))
                    $nextLevel = \App\AchievementDetail::where('cps_amount', '>', $filteredLevel->last()->cps_amount)->orderBy('cps_amount')->first();
                else
                    $nextLevel = \App\AchievementDetail::where('cps_amount', '>', 0)->orderBy('cps_amount')->first();
            }
        } else {
            if (sizeof($direct) == 1) {
                $power = ($this->lifetime_protected) ? 1 : 0;
                $first = ($this->lifetime_protected) ? 80000000 : (float) array_shift($direct)['total_investment'];
                $second = ($this->lifetime_protected) ? (float) array_shift($direct)['total_investment'] : 0;
                $rest = 0;
            } else {
                $power = ($this->lifetime_protected) ? 1 : 0;
                $first = ($this->lifetime_protected) ? 80000000 : 0;
                $second = 0;
                $rest = 0;
            }
            $nextLevel = \App\AchievementDetail::where('cps_amount', '>', 0)->orderBy('cps_amount')->first();
        }
        return array('first' => $first, 'second' => $second, 'rest' => $rest, 'power' => $power, 'achieved' => ((!is_null($filteredLevel) ? $filteredLevel : $filteredLevel)), 'next' => $nextLevel);
    }

    public function remainingCapping()
    {
        $plans = \App\StackingDeposite::where('userid', $this->id)->get();
        $remaining = 0;
        if (sizeof($plans)) {
            foreach ($plans as $plan) {
                $remaining += Crypt::decrypt($plan->capamount);
            }
        }
        return $remaining;
    }
    public function poolIncomes()
    {
        return $this->hasMany('App\PoolIncome', 'userid', 'id');
    }

    public function getPoolQualifications()
    {
        $selfInv = (float) ($this->current_self_investment);
        $legs = $this->getLegBusiness();
        $power = $legs['power'];
        $weaker = $legs['weaker'];

        $directs100 = \App\UserDetails::where('user_details.sponsorid', $this->userid)
            ->join('stacking_deposites as sd', 'sd.userid', '=', 'user_details.id')
            ->where('sd.usdt', '>=', 100)
            ->where('sd.status', 1)
            ->distinct('user_details.id')
            ->count('user_details.id');

        $dailyQual = ($selfInv >= 100);
        $weeklyQual = ($selfInv >= 100 && $directs100 >= 5);
        $monthlyQual = ($selfInv >= 100 && $directs100 >= 15 && $power >= 5000 && $weaker >= 5000);

        return [
            'self_investment' => $selfInv,
            'directs_100' => $directs100,
            'power_leg' => $power,
            'weaker_leg' => $weaker,
            'daily' => [
                'name' => 'Daily Pool',
                'pool_type' => 1,
                'percent' => '1.5%',
                'is_qualified' => $dailyQual,
                'req_self' => 100,
                'current_self' => $selfInv,
                'progress_pct' => min(100, round(($selfInv / 100) * 100)),
            ],
            'weekly' => [
                'name' => 'Weekly Pool',
                'pool_type' => 2,
                'percent' => '1.5%',
                'is_qualified' => $weeklyQual,
                'req_self' => 100,
                'req_directs' => 5,
                'current_directs' => $directs100,
                'progress_pct' => min(100, round(($directs100 / 5) * 100)),
            ],
            'monthly' => [
                'name' => 'Monthly Pool',
                'pool_type' => 3,
                'percent' => '2.0%',
                'is_qualified' => $monthlyQual,
                'req_self' => 100,
                'req_directs' => 15,
                'current_directs' => $directs100,
                'req_power' => 5000,
                'req_weaker' => 5000,
                'current_power' => $power,
                'current_weaker' => $weaker,
                'progress_pct' => min(100, round((min($directs100 / 15, $power / 5000, $weaker / 5000)) * 100)),
            ]
        ];
    }

    public function rankIncome()
    {
        return $this->hasMany('\App\RankIncome', 'userid', 'id')->get();
    }

    public function getRankQualification()
    {
        $legs = $this->getLegBusiness();
        $power = (float)($legs['power'] ?? 0);
        $weaker = (float)($legs['weaker'] ?? 0);

        $allRanks = \App\RankDetail::where('status', 1)->orderBy('rank_level', 'asc')->get();
        if ($allRanks->isEmpty()) {
            $ranksData = [
                (object)['id' => 1, 'rank_name' => 'V1', 'rank_level' => 1, 'power_leg' => 500, 'weaker_leg' => 500, 'weekly_reward' => 5],
                (object)['id' => 2, 'rank_name' => 'V2', 'rank_level' => 2, 'power_leg' => 1000, 'weaker_leg' => 1000, 'weekly_reward' => 10],
                (object)['id' => 3, 'rank_name' => 'V3', 'rank_level' => 3, 'power_leg' => 5000, 'weaker_leg' => 5000, 'weekly_reward' => 25],
                (object)['id' => 4, 'rank_name' => 'V4', 'rank_level' => 4, 'power_leg' => 10000, 'weaker_leg' => 10000, 'weekly_reward' => 50],
                (object)['id' => 5, 'rank_name' => 'V5', 'rank_level' => 5, 'power_leg' => 25000, 'weaker_leg' => 25000, 'weekly_reward' => 100],
                (object)['id' => 6, 'rank_name' => 'V6', 'rank_level' => 6, 'power_leg' => 50000, 'weaker_leg' => 50000, 'weekly_reward' => 250],
                (object)['id' => 7, 'rank_name' => 'V7', 'rank_level' => 7, 'power_leg' => 100000, 'weaker_leg' => 100000, 'weekly_reward' => 500],
                (object)['id' => 8, 'rank_name' => 'V8', 'rank_level' => 8, 'power_leg' => 500000, 'weaker_leg' => 500000, 'weekly_reward' => 2000],
            ];
        } else {
            $ranksData = $allRanks;
        }

        $highestRank = null;
        $nextRank = null;
        $matrix = [];

        foreach ($ranksData as $r) {
            $rObj = (object)$r;
            $plReq = (float)$rObj->power_leg;
            $wlReq = (float)$rObj->weaker_leg;
            $reward = (float)$rObj->weekly_reward;
            $isAchieved = ($power >= $plReq && $weaker >= $wlReq);

            if ($isAchieved) {
                $highestRank = $rObj;
            } elseif (is_null($nextRank)) {
                $nextRank = $rObj;
            }

            $plProg = $plReq > 0 ? min(100, round(($power / $plReq) * 100, 1)) : 100;
            $wlProg = $wlReq > 0 ? min(100, round(($weaker / $wlReq) * 100, 1)) : 100;
            $overallProg = min($plProg, $wlProg);

            $matrix[] = [
                'id' => $rObj->id ?? 0,
                'rank_name' => $rObj->rank_name,
                'rank_level' => (int)$rObj->rank_level,
                'power_leg' => $plReq,
                'weaker_leg' => $wlReq,
                'weekly_reward' => $reward,
                'is_achieved' => $isAchieved,
                'pl_progress' => $plProg,
                'wl_progress' => $wlProg,
                'overall_progress' => $overallProg,
            ];
        }

        $currentRankName = $highestRank ? $highestRank->rank_name : 'None';
        $currentRankLevel = $highestRank ? (int)$highestRank->rank_level : 0;
        $currentWeeklyReward = $highestRank ? (float)$highestRank->weekly_reward : 0.0;

        $nextRankName = $nextRank ? $nextRank->rank_name : null;
        $nextPlReq = $nextRank ? (float)$nextRank->power_leg : 0.0;
        $nextWlReq = $nextRank ? (float)$nextRank->weaker_leg : 0.0;
        $nextPlNeeded = $nextRank ? max(0.0, $nextPlReq - $power) : 0.0;
        $nextWlNeeded = $nextRank ? max(0.0, $nextWlReq - $weaker) : 0.0;
        $nextPlProgress = ($nextRank && $nextPlReq > 0) ? min(100.0, round(($power / $nextPlReq) * 100, 1)) : 100.0;
        $nextWlProgress = ($nextRank && $nextWlReq > 0) ? min(100.0, round(($weaker / $nextWlReq) * 100, 1)) : 100.0;
        $nextOverallProgress = min($nextPlProgress, $nextWlProgress);

        return [
            'power_leg' => $power,
            'weaker_leg' => $weaker,
            'current_rank' => $currentRankName,
            'current_rank_level' => $currentRankLevel,
            'weekly_reward' => $currentWeeklyReward,
            'highest_rank' => $highestRank,
            'next_rank' => $nextRank,
            'next_rank_name' => $nextRankName,
            'next_pl_req' => $nextPlReq,
            'next_wl_req' => $nextWlReq,
            'next_pl_needed' => $nextPlNeeded,
            'next_wl_needed' => $nextWlNeeded,
            'next_pl_progress' => $nextPlProgress,
            'next_wl_progress' => $nextWlProgress,
            'next_overall_progress' => $nextOverallProgress,
            'matrix' => $matrix,
        ];
    }
}

