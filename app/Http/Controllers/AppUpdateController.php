<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class AppUpdateController extends Controller
{
    public function AdminROITopupIncomeReturn(){
        $getAllDeposit=\App\StackingDeposite::where([['status',4],['planid','>',0],['created_at','<',date('Y-m-d')]])->get();
        $profileStore=\App\ProfileStore::where('id',1)->first();
        foreach($getAllDeposit as $deposit){
            $loanStatus=(((!is_null($deposit->userDetail()->userLoanStatus()) && $deposit->userDetail()->userLoanStatus()->status==0 && $deposit->userDetail()->userLoanStatus()->remaining==0) || is_null($deposit->userDetail()->userLoanStatus()))?0:3);
            if($loanStatus!=3 && 
                !is_null($deposit->walletTransfer()) && 
                $deposit->usdt==$deposit->walletTransfer()->amount &&
                $deposit->userDetail()->user()->permission==1 &&
                $deposit->userDetail()->capping !=1
            ){
                $realAmount=$deposit->userDetail()->stackingDeposite()->where('staketype',1)->sum('usdt');
                $requiredAmount=$deposit->usdt*30/100;
                if (($realAmount>0 && $realAmount>=$requiredAmount) || $deposit->userDetail()->silver_protected==1) {
                    
                    $cps=$deposit->usdt*.26/100;
                    \Log::info('for planid '.$deposit->id.' cps BC '.$cps);
                    $cappingFunction=new StackingDetailController();
                    $returnAmount=$cappingFunction->cappingCalculation($deposit->userid,$cps);\Log::info('Return Amount AC '.$returnAmount);
                    $insIncomeEntry=\App\CpsIncome::create([
                        'userid'        =>  $deposit->userid,
                        'txnid'         =>  $deposit->id,
                        'amount'        =>  $returnAmount/$profileStore->price,
                        'remaining'     =>  $returnAmount/$profileStore->price,
                        'amt_usdt'      =>  $returnAmount,
                        'remaining_usdt'      =>  $returnAmount,
                        'status'        =>  0,
                        'levelincome'        =>  0,
                        'created_at'    =>  now(),
                    ]);
                }
                
            }
        }
    }




    public function checkCronjob(){
        \Log::info('Cron WOrking');
    }


    public function resetAllCappingAmount(){
        $getAllUser=collect(\App\LevelIncome::all()->pluck('userid'))->unique()->values();
        foreach($getAllUser as $user){\Log::info($user);
            $getAllDeposit=\App\StackingDeposite::where([['userid',$user]])->get();
            $allIncome=\App\UserDetails::where('id',$user)->first()->totalIncomeUSDT();
            foreach($getAllDeposit as $deposit){\Log::info('All Income '.$allIncome);
                //if($allIncome>0){
                    $plan=\App\StackingDetail::where([['min_amount','<=',$deposit->usdt],['max_amount','>=',$deposit->usdt],['status',1]])->first();
                    $capping=($deposit->staketype<3)?(($deposit->userDetail()->active_direct)?5*$deposit->usdt:$plan->capping*$deposit->usdt):10*$deposit->usdt;\Log::info('Stack type '.$deposit->staketype.' amount '.$deposit->usdt.' $capping '. $capping);
                    if($capping<$allIncome){
                        $cappedAmount=Crypt::encrypt(0);
                        $status=0;
                         $allIncome-=$capping;
                     }else{
                         $cappedAmount=Crypt::encrypt(($capping-$allIncome));
                        $status=($deposit->staketype<3)?1:$deposit->staketype;
                        $allIncome=0;
                    }
                    $updateCapping=\App\StackingDeposite::where('id',$deposit->id)->update([
                        'capamount'  =>  $cappedAmount,
                        'status'  =>  $status,
                    ]);
                /*}else{
                    break;
                }*/
            }
        }
    }

    /*public function expireWithdrawWithoutOtp(){
        $getAllWithdrawOtp=\App\TransactionDetail::where([['txntype',1],['paymentstatus','<',2],['planid',0],['created_at','<',date('Y-m-d H:i:s',strtotime('- 2 hours',strtotime(now())))]])->get();
        foreach($getAllWithdrawOtp as $withdraw){
            $updateWithraw=\App\TransactionDetail::where('id',$withdraw->id)->update([
                'paymentstatus'  =>  5,
            ]);
        }
    }*/

}

