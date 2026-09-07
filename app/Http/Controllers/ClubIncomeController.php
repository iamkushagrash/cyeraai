<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StackingDetailController;

class ClubIncomeController extends Controller
{
    public function clubDistribution(){
        if(date('d')==01/*date('t')*/){
            $getAllPaidUser=\App\UserDetails::where('userstatus','>',0)->get();
            $getAllClubInfo=\App\ClubDetails::where('status',1)->get();
            $profileStore=\App\ProfileStore::where('id',1)->first();
            $capping=new StackingDetailController();
            foreach($getAllPaidUser as $user){//dd($user->clubBusiness()['first']);
                $powerPrevious=0;$weakPrevious=0;$newBusiness=0;$newPower=0;$newWeaker=0;
                //$totalturnover=$user->total_investment-$user->total_self_investment;
                $userClub=$user->clubBusiness()['achieved'];
                //\Log::info('user club ');\Log::info($userClub);
                $clubId=(!is_null($userClub) && $user->clubuser>$userClub->id)?$user->clubuser:((!is_null($userClub))?$userClub->id:0);
                $userClub=$getAllClubInfo->where('id',$clubId)->first();
                //\Log::info($userClub);
                if(!is_null($userClub)){
                    $userRecivedClub=\App\ClubIncome::where([['userid',$user->id],['clubid',$clubId]])->get();//dd(sizeof($userRecivedClub));
                    $newPower=$user->clubBusiness()['first'];
                    $newWeaker=$user->clubBusiness()['rest'];
                    $power=$user->clubBusiness()['power'];
                    if(sizeof($userRecivedClub)>=($userClub->new_business_month-1)){
                        $newBusiness=1;
                        $powerPrevious=$userRecivedClub->last()->power;
                        $weakPrevious=$userRecivedClub->last()->weaker;
                    }
                   /* \Log::info('newBusiness '.$newBusiness.' new power '.($newPower>=($powerPrevious* (100+$userClub->new_business)/100)).' new Weaker '.($newWeaker>=($weakPrevious* (100+$userClub->new_business)/100)));
                    \Log::info('60% '.(($userClub->business_min*$userClub->powerline/100)<= $newPower) );
                    \Log::info('40% '.(($userClub->business_min*$userClub->remainingline/100)<= $newWeaker));*/
                    if(
                        ($power && ($newWeaker>=($weakPrevious* (100+$userClub->new_business)/100))) ||
                        (    
                            (
                                (
                                    $newBusiness && 
                                    ($newPower>=($powerPrevious* (100+$userClub->new_business)/100)) && 
                                    ($newWeaker>=($weakPrevious* (100+$userClub->new_business)/100))
                                ) || 
                                (!$newBusiness)
                            ) &&
                            (
                                ((($userClub->business_min*$userClub->powerline/100)<= $newPower) || $user->power_protected) &&
                                ($userClub->business_min*$userClub->remainingline/100)<= $newWeaker
                            ) && 
                            (
                                $user->user()->permission==1 && $user->userstatus>0
                            )
                        )
                    ){
                        $clubAmount=$capping->cappingCalculation($user->id,$userClub->cps_amount);\Log::info('club income bC '.$userClub->cps_amount.' AC '.$clubAmount);
                        $insertClub=\App\ClubIncome::create([
                            'userid'  =>  $user->id,
                            'clubid'  =>  $clubId,
                            'amount'  =>  $clubAmount/$profileStore->price,
                            'remaining'  =>  $clubAmount/$profileStore->price,
                            'amt_usdt'  =>  $clubAmount,
                            'remaining_usdt'  =>  $clubAmount,
                            'txnDesc'  =>  'c',
                            'power'  =>  $newPower,
                            'weaker'  =>  $newWeaker,
                            'created_at'  =>  now(),
                        ]);
                    }
                }
            }
        }
    }

    public function achievementRewardDistribution(){
        //dd(date('w')==0);
        if (date('w')==0) {
            $getAllPaidUser=\App\UserDetails::where('userstatus',1)->get();
            $getAllAchievement=\App\AchievementDetail::where('status',1)->get();
            $capping=new StackingDetailController();
            $profile=\App\ProfileStore::where('id',1)->first();
            foreach($getAllPaidUser as $user){
                $business=$user->lifetimeAchievementBusiness();
                $arr=['first'=>$business['first'],'second'=>$business['second'],'rest'=>$business['rest']];//dd(is_array($arr),array_sum($arr),$getAllAchievement->min('business_min'));
                if(array_sum($arr)>=$getAllAchievement->min('business_min')){
                    $filterAchievementAll=$business['achieved'];//$getAllAchievement->where('business_min','<=',array_sum($arr));//dd($filterAchievement->business_min,($filterAchievement->remainingline*$filterAchievement->business_min/100),$arr['rest']);
                    
                    if(!is_null($filterAchievementAll)) {
                        foreach($filterAchievementAll as $filterAchievement){
                        //$filterAchievement=$filterAchievementAll->last();
                            if(
                                !is_null($filterAchievement) &&
                                (($filterAchievement->firstline*$filterAchievement->business_min/100)<=$arr['first']) &&
                                (($filterAchievement->secondline*$filterAchievement->business_min/100)<=$arr['second']) &&
                                (($filterAchievement->remainingline*$filterAchievement->business_min/100)<=$arr['rest']) 
                            ){\Log::info(' userid '.$user->id);
                                $count=\App\AchievementIncome::where([['userid',$user->id],['achievementid',$filterAchievement->id]])->count();//dd($count);
                                $amount=$filterAchievement->cps_amount;\Log::info(' Achievement bc '.$amount);
                                //$amount=$capping->cappingCalculation($user->id,$amount);\Log::info(' Achievement Ac '.$amount);
                                if(!$count && $amount>0){
                                    $insertAchievement=\App\AchievementIncome::create([
                                        'userid'  =>  $user->id,
                                        'achievementid'  =>  $filterAchievement->id,
                                        'amount'  =>  $amount/*/$profile->price*/,
                                        'remaining'  =>  $amount/*/$profile->price*/,
                                        'amt_usdt'  =>  $amount,
                                        'remaining_usdt'  =>  $amount,
                                        'txnDesc'  =>  'reward',
                                        'created_at'  =>  now(),
                                        'updated_at'  =>  now(),
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function userAchievementStatus($userid){
        $d=\App\UserDetails::where('sponsorid',$userid)->orderBy('total_investment','desc')->get();
        $direct=$d->all();
        $arr=[];
        if (sizeof($direct)>=2) {
            $arr['first']=(float)array_shift($direct)['total_investment'];
            $arr['second']=(float)array_shift($direct)['total_investment'];
            $arr['rest']=(($d->sum('total_investment'))-array_sum($arr));//dd($arr);
        }
        else{
            $arr['first']=0;
            $arr['second']=0;
            $arr['rest']=0;
        }
        return $arr;
    }


    public function leadershipIncomeDaily(){
        $calculationDate=date("Y-m-d");
        $getDailyCTO=\App\StackingDeposite::where([['created_at','>=',$calculationDate],['staketype',1]])->get();
        $leadershipDetail=\App\LeadershipDetails::where('status',1)->orderBy('investment')->get();
        $getAllQualifiedUser=\App\LeadershipInfo::where('total_investment','>=',$leadershipDetail->min('investment'))->get();
        $qualifiedUser=array();
        $capping=new StackingDetailController();
        foreach($getAllQualifiedUser as $user){
            $directDetail=\App\LeadershipInfo::where('sponsorid',$user->userid)->orderBy('total_investment','desc')->get();
            if (sizeof($directDetail)>0) {
                $first=(float)($directDetail->first()->total_investment);
                $rest=(($directDetail->sum('total_investment'))-$first);
                $filterLeadership=$leadershipDetail->filter(function($q)use($rest,$first){
                    return ($q->investment*0.4)<=$first && ($q->investment*0.6)<=$rest;
                });//dd($filterLeadership,$first,$rest);
                if(!is_null($filterLeadership->last())){
                    $updateLeadeship=\App\LeadershipInfo::where('id',$user->id)->update(['leadershipid'  =>  $filterLeadership->last()->id,]);
                }
            }
        }
        foreach($leadershipDetail as $leader){
            $getAllUser=\App\LeadershipInfo::where('leadershipid',$leader->id)->whereDate('leaderdate',$calculationDate)->get();
            $dailyCTO=\App\StackingDeposite::where('staketype',1)->whereDate('created_at',$calculationDate);//($leader->id==2)?dd($dailyCTO->sum('usdt'),$calculationDate): $id=11;
            $userCount=(count($getAllUser)>0)?count($getAllUser):1;
            $amount=($dailyCTO->sum('usdt')*($leader->roi/100))/$userCount;//if($leader->id==2)dd($dailyCTO->sum('usdt'),($leader->roi/100),$userCount, $amount);
            foreach($getAllUser as $user){
               $distributionAmount=$capping->cappingCalculation($user->id,$amount);//dd($user->id,$distributionAmount);
               if($distributionAmount>0){
                    $amountEntry=\App\LeaershipIncome::create([
                        'userid'  =>  $user->id,
                        'leadershipid'  => $leader->id ,
                        'amount'  =>  $distributionAmount,
                        'remaining'  =>  $distributionAmount,
                        'amt_usdt'  =>  $distributionAmount,
                        'remaining_usdt'  =>  $distributionAmount,
                        'txnDesc'  =>  $dailyCTO->sum('usdt'),
                    ]);
               }    
            }
        }
    }
}
