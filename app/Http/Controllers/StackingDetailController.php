<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\StackingDeposite;

class StackingDetailController extends Controller
{
    /**
     * Capping calculation: Deducts commission from user's active deposits' remaining capping.
     */
    public function cappingCalculation($userid, $amount) {
        $getAllDeposite = \App\StackingDeposite::where([['userid', $userid], ['status', '>', 0]])->get();
        $totalAmount = 0;
        
        foreach ($getAllDeposite as $deposit) {
            if ($amount > 0) {
                $remCap = (float)Crypt::decrypt($deposit->capamount);
                if ($remCap <= $amount) {
                    $totalAmount += $remCap;
                    $amount = $amount - $remCap;
                    
                    \App\UserDetails::where('id', $userid)->decrement('userstate');
                    
                    \App\StackingDeposite::where('id', $deposit->id)->update([
                        'capamount' => Crypt::encrypt(0),
                        'status'    => 0,
                    ]);
                } else {
                    $remainingAmount = $remCap - $amount;
                    \App\StackingDeposite::where('id', $deposit->id)->update([
                        'capamount' => Crypt::encrypt($remainingAmount),
                    ]);
                    $totalAmount += $amount;
                    $amount = 0;
                }
                \Log::info('userid ' . $deposit->userid . ' remaining Capping is ' . Crypt::decrypt($deposit->fresh()->capamount));
            }
        }
        return $totalAmount;
    }

    /**
     * Check and upgrade user's capping multiplier based on qualifications:
     * 2X: Default
     * 3X: Self >= $200 + 5 Directs (100+) + Power Leg >= 5K & Weaker Leg >= 5K
     * 5X: Self >= $500 + 15 Directs (100+) + Power Leg >= 25K & Weaker Leg >= 25K
     * 10X: Self >= $1000 + 15 Directs (200+) + Power Leg >= 50K & Weaker Leg >= 50K
     */
    public function checkAndUpgradeCapping($userid) {
        $userDetail = \App\UserDetails::where('id', $userid)->first();
        if (!$userDetail) return;

        $cappingStats = $userDetail->getCappingTier();
        $newMultiplier = $cappingStats['multiplier'];

        // Update all active deposits to at least this multiplier
        $activeDeposits = \App\StackingDeposite::where([['userid', $userid], ['status', 1]])->get();
        foreach ($activeDeposits as $deposit) {
            $totalCapExpected = $deposit->usdt * $newMultiplier;
            $currentCap = (float)Crypt::decrypt($deposit->capamount);
            
            // If current cap is less than new multiplier * deposit, upgrade it
            if ($currentCap < $totalCapExpected && $deposit->istatus < $newMultiplier) {
                $diff = $totalCapExpected - ($deposit->usdt * ($deposit->istatus ?: 2));
                if ($diff > 0) {
                    \App\StackingDeposite::where('id', $deposit->id)->update([
                        'capamount' => Crypt::encrypt($currentCap + $diff),
                        'istatus'   => $newMultiplier,
                    ]);
                }
            }
        }
    }

    /**
     * Booster check for user:
     * Booster 1: 5 Directs ($100+) in 7 days -> booster = 2 (1.0% daily CPS)
     * Booster 2: 15 Directs ($100+) in 7 days -> booster = 3 (1.5% daily CPS)
     */
    public function boosterCheckForUser($id) {
        $userDetail = \App\UserDetails::where('id', $id)->first();
        if (!$userDetail) return;

        $boosterStats = $userDetail->getBoosterStats();
        
        // If Booster 2 qualified
        if ($boosterStats['booster2_active']) {
            if ($userDetail->booster != 3) {
                \App\UserDetails::where('id', $id)->update(['booster' => 3]);
                \Log::info("User ID {$id} unlocked Booster 2 (1.5% Daily ROI)");
            }
        } 
        // If Booster 1 qualified
        elseif ($boosterStats['booster1_active']) {
            if ($userDetail->booster != 2 && $userDetail->booster != 3) {
                \App\UserDetails::where('id', $id)->update(['booster' => 2]);
                \Log::info("User ID {$id} unlocked Booster 1 (1.0% Daily ROI)");
            }
        }
    }

    /**
     * Distribute Level 2 to Level 15 Unilevel Income on Staking/Investment.
     * Level 1 is already handled by 5% Direct Referral income.
     */
    public function distributeLevelIncomeOnStaking($stakingDepositId, $stakingUserId, $amount) {
        $profileStore = \App\ProfileStore::where('id', 1)->first();
        $stakingUser = \App\UserDetails::where('id', $stakingUserId)->first();
        if (!$stakingUser) return;

        $allLevelConfigs = \App\LevelDetails::where('status', 1)->get()->keyBy('open_level');
        
        $currentSponsorId = $stakingUser->sponsorid;
        $levelDepth = 1;

        while ($currentSponsorId > 0 && $levelDepth <= 15) {
            $upline = \App\UserDetails::where('userid', $currentSponsorId)->first();
            if (!$upline) break;

            // For Level 2 to 15 (Level 1 is direct referral, handled separately)
            if ($levelDepth >= 2 && $levelDepth <= 15) {
                if (isset($allLevelConfigs[$levelDepth])) {
                    $config = $allLevelConfigs[$levelDepth];
                    $reqDirects = (int)$config->direct_count;
                    $reqTeamBiz = (float)$config->min_amount;
                    $ratePct = (float)$config->cps;

                    $uplineTeamBiz = (float)($upline->total_level_investment + $upline->total_direct_investment);
                    $uplineDirects = (int)($upline->active_direct);

                    // Check qualification:
                    $isQualified = ($uplineDirects >= $reqDirects && $uplineTeamBiz >= $reqTeamBiz);
                    $isActiveUser = ($upline->userstatus == 1 && $upline->userstate > 0 && $upline->capping != 1 && $upline->level_status != 0);

                    if ($isQualified && $isActiveUser && $ratePct > 0) {
                        $commissionUsdt = ($amount * $ratePct) / 100;
                        $finalUsdt = $this->cappingCalculation($upline->id, $commissionUsdt);

                        if ($finalUsdt > 0) {
                            \App\LevelIncome::create([
                                'userid'         => $upline->id,
                                'fromuser'       => $stakingUser->id,
                                'amount'         => $finalUsdt / $profileStore->price,
                                'remaining'      => $finalUsdt / $profileStore->price,
                                'amt_usdt'       => $finalUsdt,
                                'remaining_usdt' => $finalUsdt,
                                'txnid'          => $stakingDepositId,
                                'description'    => 'l',
                                'status'         => 0,
                                'created_at'     => now(),
                            ]);
                            \Log::info("Level {$levelDepth} income of \${$finalUsdt} credited to User {$upline->userid} from User {$stakingUser->userid}");
                        }
                    }
                }
            }

            // Move up to next sponsor in tree
            $currentSponsorId = $upline->sponsorid;
            $levelDepth++;
        }
    }

    /**
     * Batch / Realtime Business & Tree Turnover update pipeline.
     */
    public function businessUpdate() {
        $getAllStakingDeposit = \App\StackingDeposite::where([['batchstatus', 0], ['staketype', '<', 2]])->get();
        
        foreach ($getAllStakingDeposit as $staking) {
            $userDetail = \App\UserDetails::where('id', $staking->userid)->first();
            if (!$userDetail) continue;

            $status = 0;
            if ($userDetail->stackingDeposite()->where('staketype', 1)->where('batchstatus', 1)->exists()) {
                $status = 1;
            }

            // Self business
            $selfLeadership = \App\LeadershipInfo::firstOrNew([
                'userid'      => $userDetail->userid,
                'leaderdate'  => date("Y-m-d"),
                'sponsorid'   => $userDetail->sponsorid
            ]);
            $selfLeadership->save();
            $selfLeadership->increment('total_investment', $staking->usdt);
            $selfLeadership->increment('total_self_investment', $staking->usdt);
            $selfLeadership->save();

            // Direct Sponsor business
            $guiderUpdate = \App\UserDetails::where('userid', $userDetail->sponsorid)->first();
            if ($guiderUpdate) {
                $guiderLeadership = \App\LeadershipInfo::firstOrNew([
                    'userid'      => $userDetail->sponsorid,
                    'leaderdate'  => date("Y-m-d"),
                    'sponsorid'   => $guiderUpdate->sponsorid
                ]);
                $guiderLeadership->save();
                $guiderLeadership->increment('total_investment', $staking->usdt);
                $guiderLeadership->increment('total_direct_investment', $staking->usdt);
                $guiderLeadership->save();

                $guiderUpdate->increment('current_direct_investment', $staking->usdt);
                $guiderUpdate->increment('total_direct_investment', $staking->usdt);
                $guiderUpdate->increment('current_investment', $staking->usdt);
                $guiderUpdate->increment('total_investment', $staking->usdt);

                if ($status == 0) {
                    $guiderUpdate->increment('active_direct');
                    $guiderUpdate->increment('active_downline');
                }

                // Check Booster, Capping & Rank upgrades for Sponsor
                $this->boosterCheckForUser($guiderUpdate->id);
                $this->checkAndUpgradeCapping($guiderUpdate->id);
                \App\Http\Controllers\RankIncomeController::checkAndSyncUserRank($guiderUpdate->id);

                // Tree Upline Loop
                $currentGuiderId = $guiderUpdate->sponsorid;
                while ($currentGuiderId > 0) {
                    $upline = \App\UserDetails::where('userid', $currentGuiderId)->first();
                    if (!$upline) break;

                    $upline->increment('current_level_investment', $staking->usdt);
                    $upline->increment('total_level_investment', $staking->usdt);
                    $upline->increment('current_investment', $staking->usdt);
                    $upline->increment('total_investment', $staking->usdt);

                    if ($status == 0) {
                        $upline->increment('active_downline');
                    }

                    $uplineLeadership = \App\LeadershipInfo::firstOrNew([
                        'userid'      => $upline->userid,
                        'leaderdate'  => date("Y-m-d"),
                        'sponsorid'   => $upline->sponsorid
                    ]);
                    $uplineLeadership->save();
                    $uplineLeadership->increment('total_investment', $staking->usdt);
                    $uplineLeadership->increment('total_level_investment', $staking->usdt);
                    $uplineLeadership->save();

                    // Check Capping and Rank upgrades for Upline
                    $this->checkAndUpgradeCapping($upline->id);
                    \App\Http\Controllers\RankIncomeController::checkAndSyncUserRank($upline->id);

                    $currentGuiderId = $upline->sponsorid;
                }
            }

            // Distribute Level Income (Levels 2 to 15)
            $this->distributeLevelIncomeOnStaking($staking->id, $staking->userid, $staking->usdt);

            \App\StackingDeposite::where('id', $staking->id)->update([
                'batchstatus' => 1,
            ]);
        }
    }
}
