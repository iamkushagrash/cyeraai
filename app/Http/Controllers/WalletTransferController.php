<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use App\StackingDeposite;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\StackingDetailController;
use App\Http\Controllers\SupportQueryController;

class WalletTransferController extends Controller
{
    //User
    public function stakePage(){
        //$capping=\App\StackingDeposite::where('userid',\Session::get('user.id'))->first();dd(' capamount ',Crypt::decrypt($capping->capamount));
        $userDetail=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining>0){
            return redirect()->to('/User/RepayLoan')->with('warning','You have an acive loan please repay it first.');
        }

        $availbleStfc=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        
        $user=null;
        $price=\App\ProfileStore::where('id',1)->first();
        /*$user=\App\User::where('uuid',\Session::get('user.uuid'))
        ->join('user_details','users.id','=','user_details.userid')
        ->select(\DB::raw('('.\Session::get('logtime').'+ user_details.id) as id'),'uuid as uuid','email as email','usersname as name')->first();*/

        return view('user.upgrade')->with('balance',$availbleStfc)/*->with('stacking',$stackingPeriod)*/->with('user',$user)->with('price',$price);
    }


    public function getUserDetail(Request $request){
        $dataregex=$this->findUserName($request->email);
        $regex=['required','exists:users,uuid'];
        Validator::make($request->all(),[
            'userid' =>$regex])->validate();
        $availbleStfc=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        
        $price=\App\ProfileStore::where('id',1)->first();
        $user=\App\User::where('uuid',$request->userid)
        ->join('user_details','users.id','=','user_details.userid')
        ->select(\DB::raw('('.\Session::get('logtime').'+ user_details.id) as id'),'uuid as uuid','email as email','usersname as name','user_details.id as uid')->first();
        /*$stackingDeposite=\App\StackingDeposite::where('userid',$user->uid)->get()->last();
        if(!is_null($stackingDeposite)){
            $stackingPeriod=\App\StackingDetail::where([['status',1],['id','>=',$stackingDeposite->planid]])->selectRaw('id +'.(pow(51, 3)).' as id,planname,cps,max_amount as amount')->get();
        }else{
            $stackingPeriod=\App\StackingDetail::where([['status',1]])->selectRaw('id +'.(pow(51, 3)).' as id,planname,cps,max_amount as amount')->get();
        } */  

        $userDetail=\App\UserDetails::where('id',$user->id-\Session::get('logtime'))->first();
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining>0){
            return redirect()->back()->with('warning',$userDetail->user()->uuid.' User have an acive loan please repay it first.');
        }     
        
        return view('user.upgrade')->with('balance',$availbleStfc)/*->with('stacking',$stackingPeriod)*/->with('user',$user)->with('price',$price);
   }

   public function stakeMWT(Request $request){//dd($request->all());
    set_time_limit(0);

        if(!isset($request->staketype) || is_null($request->staketype)){
            $request->staketype='Cyera AIWallet';
        }

        $validator=Validator::make($request->all(),[
           'honeypotu' =>  ['required','gt:'.\Session::get('logtime')],
           'staketype'       =>   ['nullable','string'],
           /*'plan'       =>   ['required','numeric','gt:'.(pow(51,3))],*/
           'amount'     =>   ['nullable','numeric'],
           'password'   =>   ['required','string'],
        ]);

        if($validator->fails()){//dd($validator->errors(),$request->plan);
            return redirect('/User/Stake')->with('errors',$validator->errors());
        }
        if($request->staketype=='Cyera AIWallet'){
           if(fmod($request->amount, 100) != 0){
                return redirect('/User/Stake')->with('warning','amount should be multiple of 100');
            } 
        }elseif($request->staketype=='ProductWallet'){
            if(fmod($request->amount, 1000) != 0){
                return redirect('/User/Stake')->with('warning','amount should be multiple of 1000');
            }
        }
        
        $user=\App\User::where('uuid',\Session::get('user.userid'))->first();
        if(\Illuminate\Support\Facades\Hash::check($request->password, $user->password)){

            $walletAmount=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
            
            $getIncomingFund=\App\WalletTransfer::where([['userid',\Session::get('user.id')],/*['txnid','!=',0],*/['toWallet','wallet']])->get();
            $getOutgoingFund=\App\WalletTransfer::where([['fromUser',\Session::get('user.id')],['txnid',0],['fromWallet','wallet']])->get();
            $totalAmount=$getIncomingFund->sum('amount')-$getOutgoingFund->sum('amount');
            \Log::info('incomingfund '.$getIncomingFund->sum('amount').' outgoingfund '.$getOutgoingFund->sum('amount').' total '.$totalAmount);
            $price=\App\ProfileStore::where('id',1)->first();
            //$plan=\App\StackingDetail::where('status',1)->get();
            
            if($totalAmount>=$request->amount){
             \Log::info($request->amount);
                
                \DB::beginTransaction();
                try{
                    $userUpdate=\App\UserDetails::where('id',$request->honeypotu-\Session::get('logtime'));
                    $insWalletEntry=\App\WalletTransfer::insertGetId([
                        'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                        'txnid'  =>  0,
                        'fromWallet'  =>  'wallet',
                        'toWallet'  =>  'basic',
                        'amount'  =>  $request->amount,
                        'fromUser'  => \Session::get('user.id'),
                        'created_at'  =>  date('Y-m-d H:i:s'),
                        'release_date'  =>  date('Y-m-d'),
                    ]);
                    $walletReduceEntry=\App\AccountDeposit::where('userid',\Session::get('user.id'))->update([
                        'amount'  =>  (Crypt::encrypt(Crypt::decrypt($walletAmount->amount)-$request->amount)),
                    ]);
                    if($request->staketype==='Cyera AIWallet'){
                        $plan=\App\StackingDetail::where([['min_amount','<=',$request->amount],['max_amount','>=',$request->amount],['status',1]])->first();

                        $insertWallet=StackingDeposite::insertGetId([
                            'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                            'txnid'  =>  $insWalletEntry,
                            'amount'  =>  ($request->amount/$price->price),
                            'usdt'  => ($request->amount),
                            'capamount' =>  ($userUpdate->first()->active_direct)?Crypt::encrypt($request->amount*5):Crypt::encrypt($request->amount*$plan->capping),
                            'planid'  =>  $plan->id,
                            'status'    =>  1,
                            'roidouble' => 1,
                            'created_at'  =>  date('Y-m-d H:i:s'),
                            'istatus'   =>   ($userUpdate->first()->active_direct)?1:0, 
                            'staketype'   =>   1, 
                        ]);
                        ($userUpdate->first()->active_direct)?\Log::info($request->amount*5):\Log::info($request->amount*2);
                        $cappingFunction=new StackingDetailController();
                        $userStatus=$userUpdate->first()->userstate;
                        $userUpdate->increment('userstate');
                        $userUpdate->increment('current_self_investment',$request->amount);
                        $userUpdate->increment('total_self_investment',$request->amount);
                        $userUpdate->increment('current_investment',$request->amount);
                        $userUpdate->increment('total_investment',$request->amount);
                        $userUpdate->update(['userstatus'=>1,'capping'=>0,'roi_status'=>1,]);
                        $guiderUpdate=\App\UserDetails::where('userid',$userUpdate->first()->sponsorid);
                        if(!is_null($guiderUpdate->first())){

                            /*$guiderUpdate->increment('current_direct_investment',$request->amount);
                            $guiderUpdate->increment('total_direct_investment',$request->amount);
                            $guiderUpdate->increment('current_investment',$request->amount);
                            $guiderUpdate->increment('total_investment',$request->amount);
                            if($userStatus==0){
                                $cappingFunction->boosterCheckForUser($guiderUpdate->first()->id);
                                if($guiderUpdate->first()->active_direct==0){
                                    $cappingFunction->cappingUpdate($guiderUpdate->first()->id);
                                }
                                $guiderUpdate->increment('active_direct');
                                $guiderUpdate->increment('active_downline');
                            }*/
                            $guiderDetail=\App\UserDetails::where('userid',$userUpdate->first()->sponsorid)->first();
                            if(!is_null($guiderDetail) && ($guiderDetail->userstate || !is_null($guiderDetail->userLoanStatus()))){
                                $amt=$request->amount*5/100;\Log::info('Direct Return Amount BC '.$amt);
                                $amt=$cappingFunction->cappingCalculation($guiderDetail->id,$amt);\Log::info('Direct Return Amount AC '.$amt);
                                $insertDirectIncome=\App\BonusReward::create([
                                    'userid'  =>  $guiderDetail->id,
                                    'fromuser'  =>  $userUpdate->first()->id,
                                    'amount'  =>  $amt/$price->price,
                                    'remaining'  =>  $amt/$price->price,
                                    'amt_usdt'  =>  $amt,
                                    'remaining_usdt'  =>  $amt,
                                    'txnid'  =>  $insertWallet,
                                    'description'  =>  'referral',
                                    'status'  =>  0,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                ]);
                            }
                            
                            /*$guiderid=$guiderUpdate->first()->sponsorid;
                            while($guiderid>0){
                                $guiderUpdate=\App\UserDetails::where('userid',$guiderid);
                                $guiderUpdate->increment('current_level_investment',$request->amount);
                                $guiderUpdate->increment('total_level_investment',$request->amount);
                                $guiderUpdate->increment('current_investment',$request->amount);
                                $guiderUpdate->increment('total_investment',$request->amount);
                                if($userStatus==0)
                                    $guiderUpdate->increment('active_downline');
                                $guiderid=$guiderUpdate->first()->sponsorid;
                            }*/
                        }
                        try{
                            $sendMail=new SupportQueryController();
                            $details['email']=$userUpdate->first()->user()->email;
                            $details['subject']='You have a top-up now.';
                            $details['view']='planactivationmail';
                            $details['amount']=$request->amount;
                            $details['userid']=$userUpdate->first()->user()->uuid;
                            $status=$sendMail->sendMailgun($details);
                            if(($request->honeypotu-\Session::get('logtime')) != \Session::get('user.id')){
                                $detail['email']=$user->email;
                                $detail['subject']='You just made a top-up.';
                                $detail['view']='walletReduceMail';
                                $detail['amount']=$request->amount;
                                $detail['userid']=$userUpdate->first()->user()->uuid;
                                $detail['useruuid']=\Session::get('user.userid');
                                $status=$sendMail->sendMailgun($detail);
                            }
                        }catch(Exception $e){
                            \Log::info('Error in sending Topupmail by userid '.$user->id);
                            \Log::info($e->messages());
                        }
                    }elseif($request->staketype==='ProductWallet'){
                       $plan=\App\StackingDetail::where([['min_amount','<=',$request->amount],['max_amount','>=',$request->amount],['status',2]])->first();

                        $insertWallet=StackingDeposite::insertGetId([
                            'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                            'txnid'  =>  $insWalletEntry,
                            'amount'  =>  ($request->amount/$price->price),
                            'usdt'  => ($request->amount),
                            'capamount' =>  ($userUpdate->first()->active_direct)?Crypt::encrypt($request->amount*5):Crypt::encrypt($request->amount*$plan->capping),
                            'planid'  =>  $plan->id,
                            'status'    =>  1,
                            'roidouble' => 1,
                            'created_at'  =>  date('Y-m-d H:i:s'),
                            'istatus'   =>   ($userUpdate->first()->active_direct)?1:0, 
                            'staketype'   =>   0, 
                        ]);
                        ($userUpdate->first()->active_direct)?\Log::info($request->amount*5):\Log::info($request->amount*2);
                        $cappingFunction=new StackingDetailController();
                        $userStatus=$userUpdate->first()->userstate;
                        $userUpdate->increment('userstate');
                        $userUpdate->increment('current_self_investment',$request->amount);
                        $userUpdate->increment('total_self_investment',$request->amount);
                        $userUpdate->increment('current_investment',$request->amount);
                        $userUpdate->increment('total_investment',$request->amount);
                        $userUpdate->update(['userstatus'=>1,'capping'=>0,'roi_status'=>1,]);
                        $guiderUpdate=\App\UserDetails::where('userid',$userUpdate->first()->sponsorid);
                        if(!is_null($guiderUpdate->first())){

                            /*$guiderUpdate->increment('current_direct_investment',$request->amount);
                            $guiderUpdate->increment('total_direct_investment',$request->amount);
                            $guiderUpdate->increment('current_investment',$request->amount);
                            $guiderUpdate->increment('total_investment',$request->amount);
                            if($userStatus==0){
                                $cappingFunction->boosterCheckForUser($guiderUpdate->first()->id);
                                if($guiderUpdate->first()->active_direct==0){
                                    $cappingFunction->cappingUpdate($guiderUpdate->first()->id);
                                }
                                $guiderUpdate->increment('active_direct');
                                $guiderUpdate->increment('active_downline');
                            }*/
                            $guiderDetail=\App\UserDetails::where('userid',$userUpdate->first()->sponsorid)->first();
                            if(!is_null($guiderDetail) && ($guiderDetail->userstate || !is_null($guiderDetail->userLoanStatus()))){
                                $amt=$request->amount*5/100;\Log::info('Direct Return Amount BC '.$amt);
                                $amt=$cappingFunction->cappingCalculation($guiderDetail->id,$amt);\Log::info('Direct Return Amount AC '.$amt);
                                $insertDirectIncome=\App\BonusReward::create([
                                    'userid'  =>  $guiderDetail->id,
                                    'fromuser'  =>  $userUpdate->first()->id,
                                    'amount'  =>  $amt/$price->price,
                                    'remaining'  =>  $amt/$price->price,
                                    'amt_usdt'  =>  $amt,
                                    'remaining_usdt'  =>  $amt,
                                    'txnid'  =>  $insertWallet,
                                    'description'  =>  'referral',
                                    'status'  =>  0,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                ]);
                            }
                            
                            /*$guiderid=$guiderUpdate->first()->sponsorid;
                            while($guiderid>0){
                                $guiderUpdate=\App\UserDetails::where('userid',$guiderid);
                                $guiderUpdate->increment('current_level_investment',$request->amount);
                                $guiderUpdate->increment('total_level_investment',$request->amount);
                                $guiderUpdate->increment('current_investment',$request->amount);
                                $guiderUpdate->increment('total_investment',$request->amount);
                                if($userStatus==0)
                                    $guiderUpdate->increment('active_downline');
                                $guiderid=$guiderUpdate->first()->sponsorid;
                            }*/
                        }
                        try{
                            $sendMail=new SupportQueryController();
                            $details['email']=$userUpdate->first()->user()->email;
                            $details['subject']='You have a top-up now.';
                            $details['view']='planactivationmail';
                            $details['amount']=$request->amount;
                            $details['userid']=$userUpdate->first()->user()->uuid;
                            $status=$sendMail->sendMailgun($details);
                            if(($request->honeypotu-\Session::get('logtime')) != \Session::get('user.id')){
                                $detail['email']=$user->email;
                                $detail['subject']='You just made a top-up.';
                                $detail['view']='walletReduceMail';
                                $detail['amount']=$request->amount;
                                $detail['userid']=$userUpdate->first()->user()->uuid;
                                $detail['useruuid']=\Session::get('user.userid');
                                $status=$sendMail->sendMailgun($detail);
                            }
                        }catch(Exception $e){
                            \Log::info('Error in sending Topupmail by userid '.$user->id);
                            \Log::info($e->messages());
                        }
                    }
                    \DB::commit();
                    //return redirect('/User/Stake')->with('success','Your request is submitted successfully.');
                    return redirect('/User/Stake')->with('success','The user has been upgraded. Please wait 15-45 minutes for the business update in Genealogy.');

                    
                }
                catch(Exception $e){
                    \DB::rollback();
                    \Log::info('Error for User '.\Session::get('user.id'). ' Error message is '.$e->getMessage());
                    return redirect('/User/Stake')/*->back()*/->with('warning','Error Code 1021, There is some error in stacking.');
                }
            }else{
                return redirect('/User/Stake')/*->back()*/->with('warning','Error Code 1021, There is some error in stacking.');
            }
        }else{
            return redirect('/User/Stake')->with('warning','Your entered password is wrong.');
        }
    }



}
