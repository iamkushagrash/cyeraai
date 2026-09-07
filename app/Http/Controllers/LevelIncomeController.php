<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LevelIncomeController extends Controller
{
    public function levelDistribution(){
        if(1==1/*date('d')==date('t')*/){
            $getAllPaidUser=\App\UserDetails::where('userstate','>',0)->get();
            $getAllLevelDetails=\App\LevelDetails::where('status',1)->get();
            $getAllRoyalDetails=\App\LevelDetails::where('status',2)->get();
            $profileStore=\App\ProfileStore::where('id',1)->first();
            $capping=new StackingDetailController();
            foreach($getAllPaidUser as $user){
                $distributedCps=0;$distributedRoyalty=0;$previousamount=0;
                //$allRoi=$user->stackingIncome()->where('levelincome',1)->where("created_at",'>=',date('Y-m-01'))->where('created_at','<=',date('Y-m-t'));\Log::info('Roi filtered '.$allRoi->sum('amount'));
                $allRoi=$user->stackingIncome()->where('levelincome',1)->where("created_at",'>=',date('Y-m-d 00:00:00'))->where('created_at','<=',date('Y-m-d 23:59:59'));
                
                $guiderid=$user->sponsorid;$i=1;$j=1;//\Log::info('guiderid '.$guiderid);
                while(($guiderid>0) && ($i<=count($getAllLevelDetails))){
                    $guiderDetail=\App\UserDetails::where('userid',$guiderid)->first();
                    //\Log::info('guider');\Log::info($guiderDetail);
                    //$guiderCps=($guiderDetail->level_status==2 && $guiderDetail->leveluser>=$distributedCps)?$guiderDetail->leveluser:$guiderDetail->levelStatus();
                    $guiderCps=($guiderDetail->level_status==2 && $guiderDetail->leveluser>=$distributedCps)?
                        (($guiderDetail->leveluser>=$guiderDetail->levelStatus())?
                            $guiderDetail->leveluser:$guiderDetail->levelStatus()
                        ):$guiderDetail->levelStatus();
                    //\Log::info('distributedCps '.$distributedCps.' guiderCps '.$guiderCps);
                    if(($distributedCps<$guiderCps || ($guiderDetail->level_status==2 && $guiderDetail->leveluser>$distributedCps)) && $guiderDetail->user()->permission==1 && $guiderDetail->capping!=1 && $guiderDetail->level_status!=0){
                        //\Log::info('Distribution from '.$user->id.' to '.$guiderDetail->id);
                        
                        $level=(($guiderDetail->level_status==2 && $guiderDetail->leveluser>$distributedCps)?$guiderDetail->leveluser:$guiderCps)-$distributedCps;
                        $levelAmount=$allRoi->sum('amt_usdt')*$level/100;
                        
                        $levAmount=$capping->cappingCalculation($guiderDetail->id,$levelAmount);
                        //\Log::info('final Amount AC '.$levAmount);

                        if($levAmount>0){
                            $insertLevel=\App\LevelIncome::create([
                                'userid'  =>  $guiderDetail->id,
                                'fromuser'  =>  $user->id,
                                'amount'  =>  $levAmount/$profileStore->price,
                                'remaining'  =>  $levAmount/$profileStore->price,
                                'amt_usdt'  =>  $levAmount,
                                'remaining_usdt'  =>  $levAmount,
                                'txnid'  =>  0,
                                'description'  =>  'l',
                                'created_at'  =>  now(),
                            ]);
                            $distributedCps+=$level;//\Log::info('dis '.$distributedCps);
                            //\Log::info('new distributedCps '.$distributedCps);
                            $i++;
                            $j=1;
                            $distributedRoyalty=0;
                            $previousamount=$levAmount;
                        }
                    }elseif(($distributedCps==$guiderCps && $distributedCps>0) || ($guiderDetail->level_status==2 && $guiderDetail->leveluser>$distributedCps)){
                        //\Log::info('into Royalty');
                        if($j<=count($getAllRoyalDetails)){
                        //\Log::info('Distribution from '.$user->id.' to '.$guiderDetail->id);
                        //\Log::info('j '.$j.' leveluser '.$guiderDetail->leveluser);
                        if(($guiderDetail->level_status==2 && $guiderDetail->leveluser>=$distributedCps) || $guiderDetail->user()->permission==1 && $guiderDetail->capping!=1 && $guiderDetail->level_status!=0)
                            {
                                $royalAmount=$previousamount*$getAllRoyalDetails->where('levelname',$j)->first()->cps/100;
                                //\Log::info('$royalAmount '.$royalAmount);
                                //\Log::info('previousamount '.$previousamount);
                                //\Log::info('cps '.$getAllRoyalDetails->where('levelname',$j)->first()->cps);
                                $royAmount=$capping->cappingCalculation($guiderDetail->id,$royalAmount);
                                //\Log::info('Royalty AC '.$royAmount);
                                if($royAmount>0){
                                    $insertRoyal=\App\LevelIncome::create([
                                        'userid'  =>  $guiderDetail->id,
                                        'fromuser'  =>  $user->id,
                                        'amount'  =>  $royAmount/$profileStore->price,
                                        'remaining'  =>  $royAmount/$profileStore->price,
                                        'amt_usdt'  =>  $royAmount,
                                        'remaining_usdt'  =>  $royAmount,
                                        'txnid'  =>  0,
                                        'description'  =>  'r',
                                        'created_at'  =>  now(),
                                    ]);
                                    $j++;
                                }
                            }
                        }
                    }
                    $guiderid=$guiderDetail->sponsorid;
                }
            }
        }
    }



}
