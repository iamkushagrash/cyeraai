<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;

class LoanDetailsController extends Controller
{
    //


    //User
    public function loanPage(){
        $userDetail=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        
        if(!is_null($userDetail->userLoanStatus())){
            $loan=$userDetail->userLoanStatus();
        }else{
            $loan=null;
        }
        return view('user.loanpage')->with('loan',$loan)->with('user',$userDetail);
    }


    public function getLoan(Request $request)
    {
        return redirect()->back()->with('warning','The loan feature was available only during the initial phase and has now been discontinued. Users who wish to upgrade their account must do so using their real wallet balance.');

        $userDetail=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(!is_null($userDetail->userLoanStatus()))
        {
            return redirect()->back()->with('warning','You have already avail the loan. You cannot borrow more than once.');
        }
        if(sizeof($userDetail->stackingDeposite()->get()))
        {
            return redirect()->back()->with('warning','You already have an active package in your account so you are not eligible to take a loan.');
        }

        Validator::make($request->all(),[
            'amount' => ['required','numeric','max:500'],
        ])->validate();
        //dd($request->all());
        if(fmod($request->amount, 100) != 0){
            return redirect('/User/getUserLoan')->with('warning','Amount should be multiple of 100$.');
        }
        /*$maxAttempts = ($userDetail->id % 2 == 0) ? 2 : 3;
          if (is_null($userDetail->loan_attempts)) {
            $userDetail->loan_attempts = 1;
            $userDetail->save();
            return redirect()->back()->with('warning','We\'re checking your eligibility...Please Try again after some time.');
        }
        elseif ($userDetail->loan_attempts < $maxAttempts) {
            $userDetail->loan_attempts += 1;
            $userDetail->save();

            $msg = '';
            if ($userDetail->loan_attempts == 1) $msg = 'Please wait, your profile is under review. Please Try again after some time.';
            elseif ($userDetail->loan_attempts == 2) $msg = 'Final verification in progress.Please Try again after some time.';
            elseif ($userDetail->loan_attempts == 3) $msg = 'Ready to release your loan soon. Please Try again after some time.';

            return redirect()->back()->with('warning', $msg);
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
                /*if($guiderDetail->userstate || !is_null($guiderDetail->userLoanStatus())){
                    $fiveBonus=\App\BonusReward::create([
                      'userid'  =>  $guiderDetail->id,
                      'fromuser'  =>  $userDetail->id,
                      'amount'  =>  ($loanAmount*5/100),
                      'remaining'  =>  ($loanAmount*5/100),
                      'txnid'  =>  $insertWallet,
                      'status'  =>  3,
                      'description'  =>  'referral',
                      'created_at'  =>  date('Y-m-d H:i:s'),
                    ]);
                }*/
                
            \DB::commit();

            return redirect()->back()->with('success','Loan Amount $'.$loanAmount.' released successfully. Your account is activated.');
            }
            catch(Exception $e){
                \DB::rollback();
                \Log::info('Error for User '.\Session::get('user.id'). ' Error message is '.$e->getMessage());
                return redirect()->back()->with('warning','You have some issue with Loan. Please Try again.');
            }
        }
    }


    public function loanRepaymentPage(){
        $userDetail=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        $amount=0;
        $loantxnhistory=array();
        if(!is_null($userDetail->userLoanStatus())){
            $amount=$userDetail->userLoanStatus()->remaining;
            $loantxnhistory=\App\LoanTransactions::where([['txntype',1],['loanid',$userDetail->userLoanStatus()->id]])->get();
        }else{
            return redirect()->back()->with('warning','You do not have any loan.');
        }
        if(!is_null(\App\AccountDeposit::where('userid',\Session::get('user.id'))->first())){
            $walletamount=Crypt::decrypt(\App\AccountDeposit::where('userid',\Session::get('user.id'))->first()->amount);
        }else{
            $walletamount=0;
        }
        //dd(count($loantxnhistory));
        return view('user.loanrepayment')->with('amount',$amount)->with('walletamount',$walletamount)->with('loantxnhistory',$loantxnhistory);
    }

    public function loanRepayment(Request $request){
        $userDetail=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(is_null($userDetail->userLoanStatus()))
        {
            return redirect()->back()->with('warning','You do not have any loan.');
        }
        Validator::make($request->all(),[
            'amount' => ['required','numeric','lte:'.$userDetail->userLoanStatus()->remaining],
        ])->validate();
        $remainingLoan=$userDetail->userLoanStatus()->remaining;
        if($request->amount<0.01){
            return redirect('/User/RepayLoan')->with('warning','Please enter greater amount.');
        }
        $walletAmount=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        $getIncomingFund=\App\WalletTransfer::where([['userid',\Session::get('user.id')],['fromWallet','deposite'],['toWallet','wallet']])->get();
        $getOutgoingFund=\App\WalletTransfer::where([['fromUser',\Session::get('user.id')],['txnid',0],['fromWallet','wallet']])->get();
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
            $walletReduceEntry=\App\AccountDeposit::where('userid',\Session::get('user.id'))->update([
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
            return redirect('/User/RepayLoan')->with('success','Your repayment of loan against remaining loan amount $'.$remainingLoan.' $'.$request->amount.' deposited successfully.');
        }else{
            return redirect('/User/RepayLoan')/*->back()*/->with('warning','Error Code 1022, There is some error in repaying or you do not have enough wallet balance');
        }
        
    }

    public function loanReaymentSchedule(){
        $getAllLoan=\App\LoanDetails::where([['remaining','>',0],['status',1]])->get();
            $checkdate=date('Y-m-d',strtotime('- 45 days',strtotime(now())));
        foreach($getAllLoan as $loan){
            if($loan->created_at<$checkdate && $loan->remaining >0){
                $userUpdate=\App\User::where('id',$loan->userDetail()->user()->id)->update([
                    'permission'  >   0,   
                ]);
                $loanStatusChange=\App\StackingDeposite::where('id',$loan->walletTransfer()->stackingDeposite()->id)->update([
                    'status'  =>  4,
                ]);
            }
        }
    }

    public function userRepaymentHistory(Request $request){
        
        $reportloan=DB::table('loan_details')->where([['loan_details.userid',Session::get('user.id')],['loan_transactions.txntype',1]])
        ->join('loan_transactions','loan_transactions.loanid','=','loan_details.id')
        ->select('loan_transactions.amount as amount', 'loan_transactions.created_at as created_at')
        ->orderBy('loan_transactions.id', 'desc')
        ->get();
        /*dd($reportbasic);*/
        return view('user.loanrepaymenthistory')->with('reportloan',$reportloan);
    }


}
