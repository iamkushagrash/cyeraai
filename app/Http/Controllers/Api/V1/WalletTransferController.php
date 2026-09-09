<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Controllers\StackingDetailController;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class WalletTransferController extends BaseController
{
    //Stake
    public function stakePage(Request $request){
        
        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining>0){
            return $this->sendError('Validation Error', ['errors'   => 'You have an active loan. Please repay it first.']);
        }

        if(!is_null(\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first())){
            $amt=Crypt::decrypt(\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first()->amount);
        }else{
            $amt=0;
        }
        
        $price=\App\ProfileStore::where('id',1)->first();
        $staketype = [
            'Cyera AIWallet', 
            'ProductWallet'
        ];

        $data['available_usdt']=$amt;
        $data['price']=$price->price;
        $data['staketype']=$staketype;
        
        return $this->sendResponse($data,'Upgrade Page');
    }


    public function getUserDetail(Request $request){
        $dataregex=$this->findUserName($request->userid);
        $regex=['required','exists:users,uuid'];
        $vali=Validator::make($request->all(),[
            'userid' =>$regex ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors());
        }
        if(!is_null(\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first())){
            $amt=Crypt::decrypt(\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first()->amount);
        }else{
            $amt=0;
        }
        
        $price=\App\ProfileStore::where('id',1)->first();

        $staketype = [
            'Cyera AIWallet', 
            'ProductWallet'
        ];

        $data['available_usdt']=$amt;
        $data['price']=$price->price;
        $data['staketype']=$staketype;
        
        $data['user']=\App\User::where('uuid',$request->userid)
        ->join('user_details','users.id','=','user_details.userid')
        ->select(\DB::raw('('.pow($request->user()->userDetails()->first()->id,3).'* user_details.id) as id'),'uuid as userid','usersname as name','email as email')->first();

        $userDetail=\App\UserDetails::where('id',$data['user']->id/pow($request->user()->userDetails()->first()->id,3))->first();
        //dd($userDetail);
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining>0){
            return $this->sendError('Validation Error', ['errors'   => 'User have an active loan. Please repay it first.']);
        }     
                
        return $this->sendResponse($data,'Stake Page'); 
   }

   public function stakeMWTApi(Request $request){
       return $this->stakeCAIApi($request);
   }

   public function stakeCAIApi(Request $request){
        set_time_limit(0);
        $datareg=$this->findUserName($request->userid);
        $regex=['required','exists:users,'.$datareg['type']];
        $price=\App\ProfileStore::where('id',1)->first();


        if(!isset($request->staketype) || is_null($request->staketype)){
            $request->staketype='Cyera AIWallet';
        }

        if($request->staketype=='Cyera AIWallet'){
            if (isset($request->amount)) {
                if (fmod($request->amount, 100) != 0) {
                    return $this->sendError('Validation Error', ['errors'   => 'Please enter amount in multiple of 100$.']);
                }
            }else{
                return $this->sendError('Validation Error', ['errors'   => 'Please enter amount']);
            }
        }elseif($request->staketype=='ProductWallet'){
            if (isset($request->amount)) {
                if (fmod($request->amount, 1000) != 0) {
                    return $this->sendError('Validation Error', ['errors'   => 'Please enter amount in multiple of 100$.']);
                }
            }else{
                return $this->sendError('Validation Error', ['errors'   => 'Please enter amount']);
            }
        }

        $validator=Validator::make($request->all(),[
           'userid' =>  $regex,
           'amount'     =>   ['nullable','numeric'],
           'type'       =>   ['required','string'],
           'staketype'       =>   ['nullable','string'],
           'password'   =>   ['required','string'],
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        $user=\App\User::where('uuid',$request->user()->uuid)->first();

        if(\Illuminate\Support\Facades\Hash::check($request->password, $user->password)){
            $toUser=\App\User::where('uuid',$request->userid)->first();

            $walletAmount=\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first();
            
            $getIncomingFund=\App\WalletTransfer::where([['userid',$request->user()->userDetails()->first()->id],/*['txnid','!=',0],*/['toWallet','wallet']])->get();
            $getOutgoingFund=\App\WalletTransfer::where([['fromUser',$request->user()->userDetails()->first()->id],['txnid',0],['fromWallet','wallet']])->get();
            $totalAmount=$getIncomingFund->sum('amount')-$getOutgoingFund->sum('amount');
            //\Log::info('incomingfund '.$getIncomingFund->sum('amount').' outgoingfund '.$getOutgoingFund->sum('amount').' total '.$totalAmount);
            
            
            if($totalAmount>=$request->amount){
             //\Log::info($request->amount);
                
                \DB::beginTransaction();
                try{
                    $userUpdate=\App\UserDetails::where('id',$toUser->userDetails()->first()->id);
                    $insWalletEntry=\App\WalletTransfer::insertGetId([
                        'userid'  =>  $toUser->userDetails()->first()->id,
                        'txnid'  =>  0,
                        'fromWallet'  =>  'wallet',
                        'toWallet'  =>  'basic',
                        'amount'  =>  $request->amount,
                        'fromUser'  => $request->user()->userDetails()->first()->id,
                        'created_at'  =>  date('Y-m-d H:i:s'),
                        'release_date'  =>  date('Y-m-d'),
                    ]);
                    $walletReduceEntry=\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->update([
                        'amount'  =>  (Crypt::encrypt(Crypt::decrypt($walletAmount->amount)-$request->amount)),
                    ]);
                    if($request->staketype==='Cyera AIWallet'){
                        $plan=\App\StackingDetail::where([['min_amount','<=',$request->amount],['max_amount','>=',$request->amount],['status',1]])->first();
                        
                        $insertWallet=\App\StackingDeposite::insertGetId([
                            'userid'  =>  $toUser->userDetails()->first()->id,
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
                        //($userUpdate->first()->active_direct)?\Log::info($request->amount*5):\Log::info($request->amount*2);
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
                                $amt=$request->amount*5/100;//\Log::info('Direct Return Amount BC '.$amt);
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
                            
                            $guiderid=$guiderUpdate->first()->sponsorid;
                            /*while($guiderid>0){
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
                            $details['email']=$toUser->userDetails()->first()->user()->email;
                            $details['subject']='You have a top-up now.';
                            $details['view']='planactivationmail';
                            $details['amount']=$request->amount;
                            $details['userid']=$toUser->userDetails()->first()->user()->uuid;
                            $status=$sendMail->sendMailgun($details);
                            if($toUser->userDetails()->first()->id != $request->user()->userDetails()->first()->id){
                                $detail['email']=$request->user()->email;
                                $detail['subject']='You just made a top-up.';
                                $detail['view']='walletReduceMail';
                                $detail['amount']=$request->amount;
                                $detail['userid']=$toUser->userDetails()->first()->user()->uuid;
                                $detail['useruuid']=$request->user()->uuid;
                                $status=$sendMail->sendMailgun($detail);
                            }
                        }catch(Exception $e){
                            \Log::info('Error in sending Topupmail for userid '.$transaction->userid);
                            \Log::info($e->messages());
                        }
                    }elseif($request->staketype==='ProductWallet'){
                        $plan=\App\StackingDetail::where([['min_amount','<=',$request->amount],['max_amount','>=',$request->amount],['status',2]])->first();
                        
                        $insertWallet=\App\StackingDeposite::insertGetId([
                            'userid'  =>  $toUser->userDetails()->first()->id,
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
                        //($userUpdate->first()->active_direct)?\Log::info($request->amount*5):\Log::info($request->amount*2);
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
                                $amt=$request->amount*5/100;//\Log::info('Direct Return Amount BC '.$amt);
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
                            
                            $guiderid=$guiderUpdate->first()->sponsorid;
                            /*while($guiderid>0){
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
                            $details['email']=$toUser->userDetails()->first()->user()->email;
                            $details['subject']='You have a top-up now.';
                            $details['view']='planactivationmail';
                            $details['amount']=$request->amount;
                            $details['userid']=$toUser->userDetails()->first()->user()->uuid;
                            $status=$sendMail->sendMailgun($details);
                            if($toUser->userDetails()->first()->id != $request->user()->userDetails()->first()->id){
                                $detail['email']=$request->user()->email;
                                $detail['subject']='You just made a top-up.';
                                $detail['view']='walletReduceMail';
                                $detail['amount']=$request->amount;
                                $detail['userid']=$toUser->userDetails()->first()->user()->uuid;
                                $detail['useruuid']=$request->user()->uuid;
                                $status=$sendMail->sendMailgun($detail);
                            }
                        }catch(Exception $e){
                            \Log::info('Error in sending Topupmail for userid '.$transaction->userid);
                            \Log::info($e->messages());
                        }
                    }
                    \DB::commit();
                    //return redirect('/User/Stake')/*->back()*/->with('success','Your request is submitted successfully.');

                    
                }
                catch(Exception $e){
                     \Log::info(' Exception in Stake package upgrade');
                    \Log::info($e);
                    DB::rollback();
                    return $this->sendError('Validation Error.', ["errors"  => "Trere is some error please try again later."]);
                }
                finally{
                    
                    $price=\App\ProfileStore::where('id',1)->first();
                    if(!is_null(\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first())){
                        $amt=Crypt::decrypt(\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first()->amount);
                    }else{
                        $amt=0;
                    }
                    $staketype = [
                        'Cyera AIWallet', 
                        'ProductWallet'
                    ];

                    $data['available_usdt']=$amt;
                    
                    $data['price']=$price->price;
                    $data['staketype']=$staketype;
                    return $this->sendResponse($data,' Stake Page');
                }  
            }else{
                return $this->sendError('Validation Error.', ["errors"  => "You do not have enough balance."]);
            }
        }else{
            return $this->sendError('Validation Error.', ["errors"  => "Password is incorrect."]);
        }
    }







    //Loan
    public function loanPage(Request $request)
    {
        
        if(!is_null($request->user()->userDetails()->first()->userLoanStatus())){
            $loan=$request->user()->userDetails()->first()->userLoanStatus();

        }else{
            $loan=null;
        }
        if (sizeof($request->user()->userDetails()->first()->stackingDeposite()->get()) && is_null($loan)) {
            return $this->sendError('Validation Error', ['errors'   => 'You already have an active package in your account so you are not eligible to take a loan.']);
        }

        $data['loanamount']=$loan;
        
        return $this->sendResponse($data,'Loan Page');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function getUserLoan(Request $request)
    {
        return $this->sendError('Validation Error', ['errors'   => 'The loan feature was available only during the initial phase and has now been discontinued. Users who wish to upgrade their account must do so using their real wallet balance.']);
        
        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();
        if(!is_null($userDetail->userLoanStatus()))
        {
            return $this->sendError('Validation Error', ['errors'   => 'You have already avail the loan. You cannot borrow more than once.']);
        }
        if(sizeof($userDetail->stackingDeposite()->get()))
        {
            return $this->sendError('Validation Error', ['errors'   => 'You already have an active package in your account so you are not eligible to take a loan.']);
        }
        
        $validator=Validator::make($request->all(),[
           'amount' => ['required','numeric','max:500'],
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        if (fmod($request->amount, 100) != 0) {
            return $this->sendError('Validation Error', ['errors'   => 'Please enter amount in multiple of 100$.']);
        }
        /*$maxAttempts = ($userDetail->id % 2 == 0) ? 2 : 3;
        if (is_null($userDetail->loan_attempts)) {
            $userDetail->loan_attempts = 1;
            $userDetail->save();
            
            return $this->sendError('Validation Error', ['errors'   => 'We are checking your eligibility...Please Try again after some time.']);
        }
        elseif ($userDetail->loan_attempts < $maxAttempts) {
            $userDetail->loan_attempts += 1;
            $userDetail->save();

            $msg = '';
            if ($userDetail->loan_attempts == 1) $msg = 'Please wait, your profile is under review. Please Try again after some time.';
            elseif ($userDetail->loan_attempts == 2) $msg = 'Final verification in progress.Please Try again after some time.';
            elseif ($userDetail->loan_attempts == 3) $msg = 'Ready to release your loan soon. Please Try again after some time.';

            return $this->sendError('Validation Error', ['errors'   => 'Final verification in progress.Please Try again after some time.']);
        }
        elseif ($userDetail->loan_attempts >= $maxAttempts) {*/
        if(1==1){
            \DB::beginTransaction();
            try{
                $amountArray=[100,200,300,400];
                //$loanAmount=$amountArray[rand(0,3)];
                $loanAmount=$request->amount;
                $loanDetailEntry=\App\LoanDetails::insertGetId([
                    'userid'        =>   $userDetail->id,
                    'amount'        =>   $loanAmount,
                    'remaining'     =>   $loanAmount,
                    'status'        =>   1,
                    'created_at'    =>   now(),
                    'updated_at'    =>   now(),
                ]);
                $loanTransactionEntry=\App\LoanTransactions::create([
                    'loanid'  =>  $loanDetailEntry,
                    'amount'  =>  $loanAmount,
                    'txntype'  =>  0,
                    'created_at'  =>  now(),
                    'updated_at'  =>  now(),
                ]);
                $insWalletEntry=\App\WalletTransfer::insertGetId([
                    'userid'  =>  $userDetail->id,
                    'txnid'  =>  $loanDetailEntry,
                    'fromWallet'  =>  'loan',
                    'toWallet'  =>  'basic',
                    'amount'  =>  $loanAmount,
                    'fromUser'  => $userDetail->id,
                    'created_at'  =>  date('Y-m-d H:i:s'),
                    'release_date'  =>  date('Y-m-d'),
                ]);
                $plan=\App\StackingDetail::where('status',1)->first();
                $price=\App\ProfileStore::where('id',1)->first();
                $insertWallet=\App\StackingDeposite::insertGetId([
                    'userid'  =>  $userDetail->id,
                    'txnid'  =>  $insWalletEntry,
                    'amount'  =>  ($loanAmount/$price->price),
                    'usdt'  => ($loanAmount),
                    'capamount' =>  ($userDetail->active_direct)?Crypt::encrypt($loanAmount*5):Crypt::encrypt($loanAmount*2),
                    'planid'  =>  $plan->id,
                    'status'    =>  1,
                    'roidouble' => 1,
                    'created_at'  =>  date('Y-m-d H:i:s'),
                    'istatus'   =>   ($userDetail->active_direct)?1:0,
                    'staketype'   =>   2, 
                ]);

                $cappingFunction=new StackingDetailController();
                $guiderDetail=\App\UserDetails::where('userid',$userDetail->sponsorid)->first();
                if(!is_null($guiderDetail) && ($guiderDetail->userstate || !is_null($guiderDetail->userLoanStatus()))){
                    $amt=$loanAmount*5/100;\Log::info('Direct Return Amount BC '.$amt);
                    $amt=$cappingFunction->cappingCalculation($guiderDetail->id,$amt);\Log::info('Direct Return Amount AC '.$amt);
                    $insertDirectIncome=\App\BonusReward::create([
                        'userid'  =>  $guiderDetail->id,
                        'fromuser'  =>  $userDetail->id,
                        'amount'  =>  $amt/$price->price,
                        'remaining'  =>  $amt/$price->price,
                        'amt_usdt'  =>  $amt,
                        'remaining_usdt'  =>  $amt,
                        'txnid'  =>  $insertWallet,
                        'description'  =>  'referral',
                        'status'  =>  3,
                        'created_at'  =>  date('Y-m-d H:i:s'),
                    ]);
                }
            \DB::commit();

            
            return $this->sendResponse($this->loanPage($request),'Loan Amount $'.$loanAmount.' released successfully. Your account is topuped.');
            }
            catch(Exception $e){
                \DB::rollback();
                \Log::info('Error for User '.$request->user()->userDetails()->first()->id. ' Error message is '.$e->getMessage());
                
                return $this->sendError('Validation Error', ['errors'   => 'You have some issue with Loan. Please Try again.']);
            }
        }
    }



    public function loanRepaymentPage(Request $request)
    {   
        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();
        $amount=0;
        $loantxnhistory=array();
        if(!is_null($userDetail->userLoanStatus())){
            $loanamount=$userDetail->userLoanStatus()->amount;
            $remainingamount=$userDetail->userLoanStatus()->remaining;
            $loantxnhistory=\App\LoanTransactions::where([['txntype',1],['loanid',$userDetail->userLoanStatus()->id]])->get();
        }else{
            return $this->sendError('Validation Error', ['errors'   => 'You do not have any loan.']);
        }
        if(!is_null(\App\AccountDeposit::where('userid',$userDetail->id)->first())){
            $walletamount=Crypt::decrypt(\App\AccountDeposit::where('userid',$userDetail->id)->first()->amount);
        }else{
            $walletamount=0;
        }
        

        $data['loanamount']=$loanamount;
        $data['remainingamount']=$remainingamount;
        $data['loantxnhistory']=$loantxnhistory;
        $data['walletamount']=$walletamount;
        
        return $this->sendResponse($data,'Loan Repayment Page');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function repayUserLoan(Request $request){
        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();
        if(is_null($userDetail->userLoanStatus()))
        {
            return $this->sendError('Validation Error', ['errors'   => 'You do not have any loan.']);
        }
        $validator=Validator::make($request->all(),[
           'amount' => ['required','numeric','lte:'.$userDetail->userLoanStatus()->remaining],
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if($request->amount<0.01){
            return $this->sendError('Validation Error', ['errors'   => 'Please enter greater amount.']);
        }

        $remainingLoan=$userDetail->userLoanStatus()->remaining;

        $walletAmount=\App\AccountDeposit::where('userid',$userDetail->id)->first();
        $getIncomingFund=\App\WalletTransfer::where([['userid',$userDetail->id],['fromWallet','deposite'],['toWallet','wallet']])->get();
        $getOutgoingFund=\App\WalletTransfer::where([['fromUser',$userDetail->id],['txnid',0],['fromWallet','wallet']])->get();
        $totalAmount=$getIncomingFund->sum('amount')-$getOutgoingFund->sum('amount');

        if ($totalAmount>=$request->amount && $walletAmount>=$request->amount) {
            $loanTransactionEntry=\App\LoanTransactions::create([
                'loanid'  =>  $userDetail->userLoanStatus()->id,
                'amount'  =>  $request->amount,
                'txntype'  =>  1,
                'created_at'  =>  date('Y-m-d H:i:s'),
                'updated_at'  =>  now(),
            ]);
            $insWalletEntry=\App\WalletTransfer::create([
                'userid'  =>  $userDetail->id,
                'txnid'  =>  0,
                'fromWallet'  =>  'wallet',
                'toWallet'  =>  'loan',
                'amount'  =>  $request->amount,
                'fromUser'  => $userDetail->id,
                'created_at'  =>  date('Y-m-d H:i:s'),
                'release_date'  =>  date('Y-m-d'),
            ]);
            $reduceLoanAmount=\App\LoanDetails::where('id',$userDetail->userLoanStatus()->id)->update([
                'remaining'  =>  $remainingLoan-$request->amount,
                'updated_at'    =>  now(),
            ]);
            $walletReduceEntry=\App\AccountDeposit::where('userid',$userDetail->id)->update([
                'amount'  =>  (Crypt::encrypt(Crypt::decrypt($walletAmount->amount)-$request->amount)),
            ]);

            if($remainingLoan<=$request->amount){
                $guiderUpdate=\App\UserDetails::where('userid',$userDetail->sponsorid);
                $boosterCheck=new StackingDetailController();
                $boosterCheck->boosterCheckForUser($guiderUpdate->first()->id);
                $reduceLoanAmount=\App\LoanDetails::where('id',$userDetail->userLoanStatus()->id)->update([
                    'status'  =>  0,
                ]);

                //releasing StackingIncome
                foreach($userDetail->stackingIncome() as $roi){
                    $roiUpdate=\App\CpsIncome::where('id',$roi->id)->update([
                        'status'    =>  0,
                    ]);
                }
                // releasing levelIncome
                $levelIncome=\App\LevelIncome::where('fromuser',$userDetail->id)->get();
                foreach($levelIncome as $level){
                    $levelUpate=\App\LevelIncome::where('id',$level->id)->update([
                        'status'    =>  0,
                    ]);
                }
                // releasing clubIncome
                /*$clubIncome=\App\ClubIncome::where('fromUser',$userDetail->id)->get();
                foreach($clubIncome as $club){
                    $clubUpdate=\App\ClubIncome::where('id',$club->id)->update([
                        'status'    =>  0,
                    ]);
                }*/
                // releasing bonusIncome
                $bonusReward=\App\BonusReward::where('fromuser',$userDetail->id)->get();
                foreach($bonusReward as $bonus){
                    $bonusUpdate=\App\BonusReward::where('id',$bonus->id)->update([
                        'status'    =>  0,
                    ]);
                }
                //releasing salaryIncome
                /*foreach($userDetail->salaryIncome() as $salary){
                    $salaryUpdate=\App\SalaryIncome::where('id',$salary->id)->update([
                        'status'    =>  0,
                    ]);
                }*/
                $amount=$userDetail->userLoanStatus()->amount;
               
                $userUpdate=\App\UserDetails::where('id',$userDetail->id);
                $userStatus=$userUpdate->first()->userstatus;
                $userUpdate->increment('userstate');
                $userUpdate->increment('current_self_investment',$amount);
                $userUpdate->increment('total_self_investment',$amount);
                $userUpdate->increment('current_investment',$amount);
                $userUpdate->increment('total_investment',$amount);
                $userUpdate->update(['userstatus'=>1,'capping'=>0,'roi_status'=>1,]);

                $guiderDetail=\App\UserDetails::where('userid',$userUpdate->first()->sponsorid);
                $guiderDetail->increment('current_direct_investment',$amount);
                $guiderDetail->increment('total_direct_investment',$amount);
                $guiderDetail->increment('current_investment',$amount);
                $guiderDetail->increment('total_investment',$amount);
                if($userStatus==0){
                    $guiderDetail->increment('active_direct');
                    $guiderDetail->increment('active_downline');
                }
                //$guiderDetail->first()->boosterCheck(now());
                $guiderid=$guiderDetail->first()->sponsorid;
                while($guiderid>0){
                    $guiderUpdate=\App\UserDetails::where('userid',$guiderid);
                    $guiderUpdate->increment('current_level_investment',$amount);
                    $guiderUpdate->increment('total_level_investment',$amount);
                    $guiderUpdate->increment('current_investment',$amount);
                    $guiderUpdate->increment('total_investment',$amount);
                    if($userStatus==0)
                        $guiderUpdate->increment('active_downline');
                    $guiderid=$guiderUpdate->first()->sponsorid;
                }
            }
            return $this->sendResponse($this->loanRepaymentPage($request),'Your repayment of loan against remaining loan amount $'.$remainingLoan.' $'.$request->amount.' deposited successfully.');
        }else{
            return $this->sendError('Validation Error', ['errors'   => 'Error Code 1022, There is some error in repaying or you do not have enough wallet balance.']);
        }
        
    }


    public function userWithraw(Request $request){
        
        return $this->sendResponse($this->withdrawUserData($request),'Withdraw Data');
    }
    public function withdrawUserData($request){
        $remainingTxn=\App\TransactionDetail::where([['userid',$request->user()->userDetails()->first()->id],['txntype',1],['paymentstatus','<',2],['planid',0]])->first();
        if(is_null($remainingTxn)){
            $data['isPendingOTP']=0;
        }else{
            $data['isPendingOTP']=1;
        }
        $data['withdrawable_amount_coin']=$request->user()->userDetails()->first()->remainingIncome();
        $data['withdrawable_amount']=$request->user()->userDetails()->first()->remainingIncome();

        $price=\App\ProfileStore::where('id',1)->first();
        $data['price']=$price->price;

        $data['usdt_trc20_address']=is_null($request->user()->userDetails()->first()->assetDetail())?null:$request->user()->userDetails()->first()->assetDetail()->usdttrc20addr;
        $data['usdt_trc_address_status']=\App\ProfileStore::select('usdt_withdrawal_status')->first()->usdt_withdrawal_status;
        
        $data['usdt_bep20_address']=is_null($request->user()->userDetails()->first()->assetDetail())?null:$request->user()->userDetails()->first()->assetDetail()->usdtbep20addr;
        $data['usdt_bep20_address_status']=\App\ProfileStore::select('usdtbep20_withdrawal_status')->first()->usdtbep20_withdrawal_status;

        return $data;
    }

    public function withdrawRequest(Request $request){
        return $this->sendError('Validation Error.', ["error"=>'Withdrawal currently unavailable.']);
        if ($request->amount!=0) {
            return $this->sendError('Validation Error.', ["error"=>'Please Update Your App']);
        }
        set_time_limit(0);
        $remainingTxn=\App\TransactionDetail::where([['userid',$request->user()->userDetails()->first()->id],['txntype',1],['paymentstatus','<',2],['planid',0]])->first();
        if(is_null($remainingTxn)){
            $valid=Validator::make($request->all(),[
                'currency'  =>  ['required','string'],
                'amount'  =>  ['nullable','numeric'],
                'amountusdt'  =>  ['required','numeric'],
            ]);
            if($valid->fails()){
                return $this->sendError('Validation Error.', $valid->errors());
            }
            
            $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();
            $profile=\App\ProfileStore::where('id',1)->first();
            $request->amount=$request->amountusdt/$profile->price;
            if(!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status==0){
                return $this->sendError('Validation Error.', ["error"=>'Conversion not permitted. Please contact Admin']);
            }
            if(fmod($request->amountusdt, 10) != 0){
                return $this->sendError('Validation Error.', ["error"=>'Amount should be multiple of 10$.']);
            }

            $previousWithdrawal=\App\TransactionDetail::where([['paymentstatus','<=',2],['txntype',1],['created_at','>',date('Y-m-d H:i:s',strtotime('-24 hours',strtotime(now())))],['userid',$userDetail->id]])->orderBy('id','desc')->get();
            $amountWith=($request->amount_usdt)+$previousWithdrawal->sum('amountusdt');

            if($amountWith>500){
                return $this->sendError('Validation Error.', ["error"=>'Maximum withdraw amount is 500 $ and Your requested Withdrawal in last 24 hours is '.$previousWithdrawal->sum('amountusdt').' $']);
            }
            
            if(($userDetail->remainingIncome())>=$request->amountusdt){
                if($request->amountusdt<10){
                    return $this->sendError('Validation Error.', ["error"=>'Minimum withdraw amount is 10 $ and Your requested USDT equals to '.$request->amountusdt.' $.']);
                }
                
                if(!is_null($userDetail->assetDetail())){
                    $reducePool=0;
                    $reduceCps=0;
                    $reduceLevel=0;
                    $reduceReward=0;
                    //$reduceSalary=0;
                    /*$addr=$userDetail->assetDetail();*/
                    \DB::beginTransaction();
                    try{
                        if ($request->currency=='usdt') {
                            $addr=$userDetail->assetDetail()->usdttrc20addr;
                        }elseif ($request->currency=='usdtbep20') {
                            $addr=$userDetail->assetDetail()->usdtbep20addr;
                        }
                        $randId=rand(111111,999999);
                        $txnId=$this->insertWithdrawEntry($request->user()->userDetails()->first()->id,$request->amount,$request->amountusdt,$addr,$request->currency,$randId);
                        $data['token']=$randId;
                        $data['email']=$userDetail->user()->email;
                        $data['view']='withdrawOtpMail';
                        $data['subject']='OTP to confirm withdraw request.';
                        $data['useruuid']=$userDetail->user()->uuid;
                        $data['currency']=$request->currency;
                        $data['address']=$addr;
                        $data['amountusdt']=$request->amountusdt;
                        $data['amountcoin']=$request->amount;
                        $mailSend=new SupportQueryController();
                        $mailStatus=$mailSend->sendMailgun($data);
                        $txnIdUpdate=\App\TransactionDetail::where('id',$txnId)->update([
                            'b_status'  =>  ($mailStatus==0)?1:2,
                        ]);
                        \DB::commit();
                        return $this->sendResponse($this->withdrawUserData($request),'A mail containing OTP sent to your registered mail id. Check mail and insert OTP to confirm withdraw request and process withdraw.');
                    }
                    catch(Exception $e){
                        \DB::rollback();
                        \Log::info('Error for User '.$request->user()->userDetails()->first()->id. ' Error message is '.$e->getMessage());
                        return $this->sendError('Validation Error.', ["error"=>'You have some issue with withdraw. Please Try again.']);
                    }
                }else{
                    return $this->sendError('Validation Error.', ["error"=>'Please update wallet address first.']);
                }
            }else{
                return $this->sendError('Validation Error.', ["error"=>'You do not have enough amount to Sell.']);
            }
        }else{
            $data=[];
            $valid=Validator::make($request->all(),[
                'otp'  =>  ['required','numeric'],
            ]);
            if($valid->fails()){
                return $this->sendError('Validation Error.', $valid->errors());
            }
            $txnInfo=\App\TransactionInfo::where('txnid',$remainingTxn->id)->first();
            if($request->otp==$txnInfo->transaction_hash){
                $reducePool=0;
                $reduceCps=0;
                $reduceLevel=0;
                $reduceReward=0;
                //$reduceSalary=0;
                \DB::beginTransaction();
                try{
                    $txnId=$remainingTxn->id;
                    $updateTxn=\App\TransactionDetail::where('id',$remainingTxn->id)->update([
                        'planid'  =>  1,
                        'b_status'  =>  0,
                        'updated_at'  =>  date('Y-m-d H:i:s'),
                    ]);
                    $updateTransactionInfo=\App\TransactionInfo::where('txnid',$remainingTxn->id)->update([
                        'transaction_hash'  =>  '',
                    ]);

                    $amt=$remainingTxn->amountusdt;
                    $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();
                    //reduce cps first
                    $entry=$userDetail->stackingIncome()->where('remaining_usdt','>',0)->where('status','!=',3);
                    $reduceCps=($amt>$entry->sum('remaining_usdt'))? $entry->sum('remaining_usdt'): $amt;\Log::info('reduce cps '.$reduceCps);
                    foreach($entry as $cps){ 
                        if($amt>0){
                            $updCps=\App\CpsIncome::where('id',$cps->id)->update([
                                'remaining_usdt'    => (($amt>$cps->remaining_usdt)? 0 :($cps->remaining_usdt-$amt)),
                                ($cps->intxna==0)?'intxna':'intxnb' =>  $txnId
                            ]); 
                            $amt-=$cps->remaining_usdt;
                        }else{
                            break;
                        }\Log::info('remaining after cps '.$amt);
                    }
                    if($amt>0){
                        $entry=$userDetail->levelIncome()->where('remaining_usdt','>',0)->where('status','!=',3);
                        $reduceLevel=($amt>$entry->sum('remaining_usdt'))? $entry->sum('remaining_usdt'):$amt;\Log::info('reduce level '.$reduceLevel);
                        foreach($entry as $level){
                            if($amt>0){
                                 $updRef=\App\LevelIncome::where('id',$level->id)->update([
                                     'remaining_usdt'    => (($amt>$level->remaining_usdt)? 0 :($level->remaining_usdt-$amt)),
                                     ($level->intxna==0)?'intxna':'intxnb' =>  $txnId
                                 ]);
                                 $amt-=$level->remaining_usdt;
                             }else{
                                 break;
                             }
                         }\Log::info('remaining after level '.$amt);
                    }
                           
                    if($amt>0){
                        $entry=$userDetail->bonusReward()->where('remaining_usdt','>',0)->where('status','!=',3);
                        $reduceReward=($amt>$entry->sum('remaining_usdt'))? $entry->sum('remaining_usdt'):$amt;\Log::info('reduce bonus Reward '.$reduceReward);
                        foreach($entry as $bonus){
                            if($amt>0){
                                $updRef=\App\BonusReward::where('id',$bonus->id)->update([
                                    'remaining_usdt'    => (($amt>$bonus->remaining_usdt)? 0 :($bonus->remaining_usdt-$amt)),
                                    ($bonus->intxna==0)?'intxna':'intxnb' =>  $txnId
                                ]);
                                $amt-=$bonus->remaining_usdt;
                            }else{
                                break;
                            }
                        }\Log::info('remaining after bonus '.$amt);
                    }
                           
                    /*if($amt>0){
                        $entry=$userDetail->clubIncome()->where('remaining_usdt','>',0)->where('status','!=',3);
                        $reducePool=($amt>$entry->sum('remaining_usdt'))? $entry->sum('remaining_usdt'):$amt;\Log::info('reduce club Reward '.$reducePool);
                        foreach($entry as $club){
                            if($amt>0){
                                $updRef=\App\ClubIncome::where('id',$club->id)->update([
                                    'remaining_usdt'    => (($amt>$club->remaining_usdt)? 0 :($club->remaining_usdt-$amt)),
                                    ($club->intxna==0)?'intxna':'intxnb' =>  $txnId
                                ]);
                                $amt-=$club->remaining_usdt;
                            }else{
                                break;
                            }
                        }\Log::info('remaining after club '.$amt);
                    }*/
                    
                    if($amt>0){
                        \DB::rollback();
                        \Log::info('Error for User '.$request->user()->userDetails()->first()->id. ' Error message is income less than withdraw.');
                        return $this->sendError('Validation Error.', ["error"=>'You have some issue with withdraw. Please contact to admin.']);
                    }else{
                        $withdrawInfoInsert=\App\WithdrawInfo::create([
                            'txnid'  =>  $txnId,
                            'stacking'  =>  $reduceCps,
                            'level'  =>  $reduceLevel,
                            'bonus' =>  $reduceReward,
                            'club' =>  $reducePool,
                        ]);
                        //API CAll
                        $withFunction=$this->withdrawApiUsdt($txnInfo->payment_addr,round($remainingTxn->net_amount,2));
                        //\Log::info($withFunction);
                        //dd($withFunction,is_null($withFunction));
                        if (!is_null($withFunction) && (!isset($withFunction->status) && (isset($withFunction->Status) && $withFunction->Status==200))) {
                            $updateTxn=\App\TransactionDetail::where('id',$remainingTxn->id)->update([
                                'paymentstatus'  =>  2,
                                'remaining'  =>  0,
                                'updated_at'  =>  date('Y-m-d H:i:s'),
                            ]);
                            $updateTransactionInfo=\App\TransactionInfo::where('txnid',$remainingTxn->id)->update([
                                'transaction_hash'  =>  $withFunction->Hash,
                            ]);
                        } else{
                            $updateTxn=\App\TransactionDetail::where('id',$remainingTxn->id)->update([
                                'b_status'  =>  3,
                                'planid'  =>  2,
                                'updated_at'  =>  date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }catch(Exception $e){
                    \DB::rollback();
                    \Log::info('Error for User '.$request->user()->userDetails()->first()->id. ' Error message is '.$e->getMessage());
                    return $this->sendError('Validation Error.', ["error"=>'You have some issue with withdraw. Please Try again.']);
                }
                \DB::commit();
                return $this->sendResponse($this->withdrawUserData($request),'Your CAI Sell of amount $ '.$remainingTxn->amountusdt.' placed successfully.');
            }else{
                return $this->sendError('Validation Error.', ["error"=>'Invalid OTP. Please check and confirm and try again.']);
            }
        }
    }

    public function insertWithdrawEntry($userId,$styamount,$amount,$addr,$paymode,$randId){
        $insTxn=\App\TransactionDetail::insertGetId([
            'userid'  =>  $userId,
            'txntype'  =>  1,
            'amountsftc'  =>  ($styamount),
            'amountusdt'  =>  ($amount),
            'remaining'  => ($amount),
            'paymentstatus'  =>  1,
            'txndesc'  =>  'Withdrawal',
            'currency'  =>  $paymode,
            'comments'  =>  $paymode,
            'paidby'  =>  0,
            'release_date'  =>  date('Y-m-d'),
            'deduction' =>  ($amount*.10),/*($amount*.05)*/
            'net_amount'    =>  ($amount*.90),/*($amount*.95)*/
            'planid'    =>  0,
            'plan_status'  =>  0,
            'created_at'  =>  date('Y-m-d H:i:s'),
        ]);
        $transactionInfo=\App\TransactionInfo::create([
            'txnid'  =>  $insTxn,
            'payment_addr'  =>  $addr,
            'payee_addr'  =>  '',
            'transaction_hash'  =>  $randId,
            'created_at'  =>  date('Y-m-d H:i:s'),
        ]);
        return $insTxn;
    }

    public function withdrawApiUsdt($address,$amount){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://kjasnfbcaiunewncweiocn.vectanetcoin.com/api/withdraw',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'recipient='.$address.'&amount='.$amount,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        \Log::info('address '.$address);
        \Log::info($response);

        //\Log::info(json_decode($response)->Status);
        return json_decode($response);

    }


}
