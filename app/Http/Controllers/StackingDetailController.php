<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\StackingDeposite;

class StackingDetailController extends Controller
{
    public function cappingCalculation($userid,$amount){
        $getAllDeposite=\App\StackingDeposite::where([['userid',$userid],['status','>',0]])->get();
        $totalAmount=0;
        foreach($getAllDeposite as $deposit){
            if($amount>0){
                if(Crypt::decrypt($deposit->capamount)<=$amount){
                    $remainingAmount=Crypt::decrypt($deposit->capamount)-$amount;
                    $totalAmount+=Crypt::decrypt($deposit->capamount);
                    $amount=$amount-Crypt::decrypt($deposit->capamount);
                    $updateUserDetailUserstate=\App\UserDetails::where('id',$userid);
                    $updateUserDetailUserstate->decrement('userstate');
                    /*$updateUserDetailUserstate->save();*/
                    $updateCapping=\App\StackingDeposite::where('id',$deposit->id)->update([
                        'capamount'  =>   Crypt::encrypt(0),
                        'status'  =>   0,
                    ]);
                }else{
                    $remainingAmount=Crypt::decrypt($deposit->capamount)-$amount;
                    $updateCapping=\App\StackingDeposite::where('id',$deposit->id)->update([
                        'capamount'  =>   Crypt::encrypt($remainingAmount),
                    ]);
                    $totalAmount+=$amount;
                    $amount=0;
                }
                \Log::info('userid '. $deposit->userid.' remaining Capping Amount is '.Crypt::decrypt($deposit->capamount));
            }
        }
        return $totalAmount;
    }

    public function cappingUpdate($userid){
        $getAllPlan=\App\StackingDeposite::where([['userid',$userid],['istatus',0]])->get();
        foreach($getAllPlan as $plan){
            $updateCapping=\App\StackingDeposite::where('id',$plan->id)->update([
                'capamount'  =>  Crypt::encrypt(Crypt::decrypt($plan->capamount)+(3*$plan->usdt)),
                'istatus'  =>  1,
            ]);
        }
    }

    public function boosterCheckForUser($id){
        $userDetail=\App\UserDetails::where('id',$id)->first();
        //\Log::info('created_at check created_at='.$userDetail->created_at.' check date='.date('Y-m-d',strtotime('- 7 days',strtotime(now()))).' Answer is '.$userDetail->created_at >= date('Y-m-d',strtotime('- 7 days',strtotime(now()))));
        \Log::info('is null loan status '.is_null($userDetail->userLoanStatus()));
        if(!is_null($userDetail->stackingDeposite()->first()) && $userDetail->active_direct>=4 && ($userDetail->stackingDeposite()->first()->created_at >= date('Y-m-d',strtotime('- 7 days',strtotime(now()))) && (is_null($userDetail->userLoanStatus()) || $userDetail->userLoanStatus()->status==0))){
        \Log::info('inside if');
            $m=0;
            foreach($userDetail->totalDirect()->get() as $direct){
                \Log::info('self '.$userDetail->stackingDeposite()->max('usdt'));
                \Log::info('direct '.$direct->stackingDeposite()->max('usdt'));
                \Log::info('loan Status '.is_null($direct->userLoanStatus()));
                if(($direct->stackingDeposite()->max('usdt')>=$userDetail->stackingDeposite()->max('usdt')) && (is_null($direct->userLoanStatus()) || $direct->userLoanStatus()->status==0)){
                    $m++;
                }
            }\Log::info('m '.$m);
            if($m>=5){
                if (!is_null($userDetail->stackingDeposite()->whereNotIn('staketype', [3, 4])->first())) {
                    $userUpdate=\App\UserDetails::where('id',$id)->update([
                        'booster'  =>  2,
                    ]);
                }
            }
        }
    }

    public function businessUpdate(){
        $getAllStakingDeposit=\App\StackingDeposite::where([['batchstatus',0],['staketype','<',2]])->get();
        //dd(count($getAllStakingDeposit));
        foreach($getAllStakingDeposit as $staking){
            //dd($staking);
            $userDetail=\App\UserDetails::where('id',$staking->userid)->first();
            $status=0;
            //dd($userDetail->id);
            //dd($userDetail->stackingDeposite()->where('staketype', 1)->where('batchstatus', 1)->exists());
            if($userDetail->stackingDeposite()->where('staketype', 1)->where('batchstatus', 1)->exists()){
                $status=1;
            }
            $selfLeadership=\App\LeadershipInfo::firstOrNew(['userid' =>$userDetail->userid,'leaderdate'  =>date("Y-m-d"),'sponsorid' =>$userDetail->sponsorid]);
            $selfLeadership->save();
            $selfLeadership->increment('total_investment',$staking->usdt);
            $selfLeadership->increment('total_self_investment',$staking->usdt);
            $selfLeadership->save();
            //$cappingFunction=new StackingDetailController();
            $guiderLeadership=\App\LeadershipInfo::firstOrNew(['userid' =>$userDetail->sponsorid,'leaderdate'  =>date("Y-m-d"),'sponsorid' =>$userDetail->guiderDetails()->first()->sponsorid]);
            $guiderLeadership->save();
            $guiderLeadership->increment('total_investment',$staking->usdt);
            $guiderLeadership->increment('total_direct_investment',$staking->usdt);
            $guiderLeadership->save();
            $guiderUpdate=\App\UserDetails::where('userid',$userDetail->sponsorid);
            $guiderUpdate->increment('current_direct_investment',$staking->usdt);
            $guiderUpdate->increment('total_direct_investment',$staking->usdt);
            $guiderUpdate->increment('current_investment',$staking->usdt);
            $guiderUpdate->increment('total_investment',$staking->usdt);
            if($status==0){
                $this->boosterCheckForUser($guiderUpdate->first()->id);
                if($guiderUpdate->first()->active_direct==0){
                    $this->cappingUpdate($guiderUpdate->first()->id);
                }
                $guiderUpdate->increment('active_direct');
                $guiderUpdate->increment('active_downline');
            }
            $guiderid=$guiderUpdate->first()->sponsorid;
            while($guiderid>0){
                $guiderUpdate=\App\UserDetails::where('userid',$guiderid);
                $guiderUpdate->increment('current_level_investment',$staking->usdt);
                $guiderUpdate->increment('total_level_investment',$staking->usdt);
                $guiderUpdate->increment('current_investment',$staking->usdt);
                $guiderUpdate->increment('total_investment',$staking->usdt);
                if($status==0)
                    $guiderUpdate->increment('active_downline');
                $guiderid=$guiderUpdate->first()->sponsorid;
                $guideridLeadership=\App\LeadershipInfo::firstOrNew(['userid' =>$guiderUpdate->first()->userid,'leaderdate'  =>date("Y-m-d"),'sponsorid' =>$guiderUpdate->first()->sponsorid]);
                $guideridLeadership->save();
                $guideridLeadership->increment('total_investment',$staking->usdt);
                $guideridLeadership->increment('total_level_investment',$staking->usdt);
                $guideridLeadership->save();
            }
            $stackingUpdate=\App\StackingDeposite::where('id',$staking->id)->update([
                'batchstatus'   => 1,
            ]);
        }
    }
}
