<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\UserDetails;
use DB;
use Session;
use Illuminate\Support\Facades\Crypt;

class LevelDetailsController extends Controller
{
    //Admin
    public function searchUserforIncome(){
        return view('control.userincomesearch')->with('userdata',array());
    }

    public function searchUserIncome(Request $request){
        $datareg=$this->findUserName($request->userrid);
        $user=DB::table('users')->where([['licence','1'],['uuid',$request->userrid]])
        ->join('user_details','users.id','=','user_details.userid')
        ->select('users.id as id','users.email as email','users.uuid as userid','users.usersname as name','user_details.id as uid')
        ->get()->first();

        if (is_null($user)) {
            return redirect()->back()->with(['warning'=>'User Not Found']);
        }
        else{
            $userDetail=\App\UserDetails::where('id',$user->uid)->first();

            $totalroireceived=DB::table('cps_incomes')->where('cps_incomes.userid',$user->uid)
            ->join('stacking_deposites','stacking_deposites.id','=','cps_incomes.txnid')
            ->select('cps_incomes.amount as amount','cps_incomes.amt_usdt as amountusdt', 'stacking_deposites.amount as principal', 'stacking_deposites.usdt as principalusdt')
            ->selectRaw('DATE_FORMAT(cps_incomes.created_at,"%d-%m-%Y") as created_at')
            ->selectRaw('case when cps_incomes.status=0 then "Credit" when cps_incomes.status=1 then "Withdraw" when cps_incomes.status=3 then "Locked" end as status')
            ->selectRaw('case when cps_incomes.status=0 then "ffffffbf " when cps_incomes.status=1 then "ffffffbf " when cps_incomes.status=3 then "FF0000" end as statusclass')
            ->orderBy('cps_incomes.id', 'desc')
            ->get();
            $totaldirectreceived=DB::table('bonus_rewards')->where([['bonus_rewards.userid',$user->uid],['bonus_rewards.description','referral']])
            ->join('user_details','user_details.id','=','bonus_rewards.fromuser')
            ->join('users','users.id','=','user_details.userid')
            ->select('bonus_rewards.amount as amount','bonus_rewards.amt_usdt as amountusdt','bonus_rewards.description as level','users.uuid as fromid','users.usersname as fromname')
            ->selectRaw('DATE_FORMAT(bonus_rewards.created_at,"%d-%m-%Y") as created_at')
            ->selectRaw('case when bonus_rewards.status=0 then "Credit" when bonus_rewards.status=1 then "Withdraw" when bonus_rewards.status=3 then "Locked" end as status')
            ->selectRaw('case when bonus_rewards.status=0 then "ffffffbf" when bonus_rewards.status=1 then "ffffffbf" when bonus_rewards.status=3 then "FF0000" end as statusclass')
            ->orderBy('bonus_rewards.id', 'desc')
            ->get();
            $totalstakingreferralreceived=DB::table('level_incomes')->where([['level_incomes.userid',$user->uid],['level_incomes.description','l']])
            ->select(DB::raw('DATE_FORMAT(level_incomes.created_at ,"%Y-%m-%d")as txndate'))
            ->selectRaw('sum(level_incomes.amount) as amount,sum(level_incomes.amt_usdt) as amountusdt')
            ->orderBy('txndate', 'desc')
            ->groupBy('txndate')
            ->get();
            $totalteamdevelopmentreceived=DB::table('level_incomes')->where([['level_incomes.userid',$user->uid],['level_incomes.description','r']])
            ->select(DB::raw('DATE_FORMAT(level_incomes.created_at ,"%Y-%m-%d")as txndate'))
            ->selectRaw('sum(level_incomes.amount) as amount,sum(level_incomes.amt_usdt) as amountusdt')
            ->orderBy('txndate', 'desc')
            ->groupBy('txndate')
            ->get();
            $totalclubreceived=DB::table('club_incomes')->where('club_incomes.userid',$user->uid)
            ->join('club_details','club_details.id','=','club_incomes.clubid')
            ->select('club_incomes.amount as amount', 'club_details.clubname as clubname')
            ->selectRaw('DATE_FORMAT(club_incomes.created_at,"%d-%m-%Y") as created_at')
            ->selectRaw('case when club_incomes.status=0 then "Credit" when club_incomes.status=1 then "Withdraw" when club_incomes.status=3 then "Locked" end as status')
            ->selectRaw('case when club_incomes.status=0 then "ffffffbf " when club_incomes.status=1 then "ffffffbf " when club_incomes.status=3 then "FF0000" end as statusclass')
            ->orderBy('club_incomes.id', 'desc')
            ->get();
            $totalrewardreceived=DB::table('achievement_incomes')->where('achievement_incomes.userid',$user->uid)
            ->join('achievement_details','achievement_details.id','=','achievement_incomes.achievementid')
            ->select('achievement_incomes.amount as amount', 'achievement_details.rewardname as rewardname')
            ->selectRaw('DATE_FORMAT(achievement_incomes.created_at,"%d-%m-%Y") as created_at')
            ->selectRaw('case when achievement_incomes.status=0 then "Credit" when achievement_incomes.status=1 then "Withdraw" when achievement_incomes.status=3 then "Locked" end as status')
            ->selectRaw('case when achievement_incomes.status=0 then "ffffffbf " when achievement_incomes.status=1 then "ffffffbf " when achievement_incomes.status=3 then "FF0000" end as statusclass')
            ->orderBy('achievement_incomes.id', 'desc')
            ->get();
            $withdrawhistory=DB::table('transaction_details')->where([['transaction_details.userid',$user->uid],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal']/*,['transaction_details.paymentstatus',2]*/])
            ->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
            ->join('user_details','transaction_details.userid','=','user_details.id')
            ->join('users','user_details.userid','=','users.id')
            ->select('transaction_details.amountsftc','transaction_details.amountusdt','transaction_details.deduction','transaction_details.net_amount','transaction_details.currency', 'users.uuid', 'users.email', 'users.usersname', 'transaction_infos.transaction_hash')
            ->selectRaw('DATE_FORMAT(transaction_details.created_at,"%d-%m-%Y") as created_at')
            ->selectRaw('case when transaction_details.paymentstatus=0 then "Verification Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Cancelled" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Expired" end as status')
            ->selectRaw('case when transaction_details.paymentstatus=0 then "badge-warning" when transaction_details.paymentstatus=1 then "badge-warning" when transaction_details.paymentstatus=2 then "badge-success" when transaction_details.paymentstatus=3 then "badge-danger" when transaction_details.paymentstatus=4 then "badge-danger" when transaction_details.paymentstatus=5 then "badge-danger" end as statusclass')
            ->orderByRaw('transaction_details.id DESC')
            ->get();
            
            $activeplan=DB::table('stacking_deposites')->where('stacking_deposites.userid',$user->uid)
            ->join('user_details','stacking_deposites.userid','=','user_details.id')
            ->join('users','user_details.userid','=','users.id')
            ->join('stacking_details','stacking_deposites.planid','=','stacking_details.id')
            ->join('wallet_transfers','stacking_deposites.txnid','=','wallet_transfers.id')
            ->join('user_details as ud','wallet_transfers.fromUser','=','ud.id')
            ->join('users as u','ud.userid','=','u.id')
            ->select('stacking_deposites.amount','stacking_deposites.usdt','stacking_details.cps', 'users.email', 'users.uuid as userid', 'users.usersname as usersname', 'u.usersname as fromname', 'u.uuid as fromid')
            ->selectRaw('DATE_FORMAT(stacking_deposites.created_at,"%d-%m-%Y") as created_at')
            ->selectRaw('case when stacking_deposites.status=-1 then "Withdraw" when stacking_deposites.status=0 then "Closed" when stacking_deposites.status=1 then "Active" when stacking_deposites.status=2 then "Active" when stacking_deposites.status=3 then "Active" end as status')
            ->selectRaw('case when stacking_deposites.status=0 then "status-cancelled" when stacking_deposites.status=1 then "status-complete" when stacking_deposites.status=2 then "status-complete" when stacking_deposites.status=3 then "status-complete" end as statusclass')
            ->selectRaw('case when stacking_deposites.staketype=1 then "Wallet" when stacking_deposites.staketype=2 then "Loan" when stacking_deposites.staketype=3 then "Gold" when stacking_deposites.staketype=4 then "Silver" end as staketype')
            ->orderByRaw('stacking_deposites.id DESC')
            ->get();

            $totalwithdraw=DB::table('transaction_details')->where([['transaction_details.userid',$user->uid],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',2]])
            ->select(DB::raw('sum(amountsftc) as amountcoin,sum(amountusdt) as amountusdt'))
            ->get()->first();
            $sumroi=DB::table('cps_incomes')->where('cps_incomes.userid',$user->uid)
            ->select(DB::raw('sum(amt_usdt) as totalroi,sum(remaining_usdt) as remainingroi'))
            ->get()->first();
            $sumreferral=DB::table('level_incomes')->where([['level_incomes.userid',$user->uid],['level_incomes.description','l']])
            ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
            ->get()->first();
            $sumteamdevelopment=DB::table('level_incomes')->where([['level_incomes.userid',$user->uid],['level_incomes.description','r']])
            ->select(DB::raw('sum(amt_usdt) as totaltd,sum(remaining_usdt) as remainingtd'))
            ->get()->first();
            $sumdirect=DB::table('bonus_rewards')->where([['bonus_rewards.userid',$user->uid],['bonus_rewards.description','referral']])
            ->select(DB::raw('sum(amt_usdt) as totaldirect,sum(remaining_usdt) as remainingdirect'))
            ->get()->first();
            $sumclub=DB::table('club_incomes')->where([['club_incomes.userid',$user->uid]/*,['rank_incomes.comment','rank']*/])
            ->select(DB::raw('sum(amt_usdt) as totalclub,sum(remaining_usdt) as remainingclub'))
            ->get()->first();
            $totalincome=$sumroi->totalroi+$sumreferral->totallevel+$sumteamdevelopment->totaltd+$sumdirect->totaldirect+$sumclub->totalclub;
        
            $userdata['user']=$user;
            $userdata['userDetail']=$userDetail;
            $userdata['totalroireceived']=$totalroireceived;
            $userdata['totaldirectreceived']=$totaldirectreceived;
            $userdata['totalstakingreferralreceived']=$totalstakingreferralreceived;
            $userdata['totalclubreceived']=$totalclubreceived;
            $userdata['totalteamdevelopmentreceived']=$totalteamdevelopmentreceived;
            $userdata['totalrewardreceived']=$totalrewardreceived;
            $userdata['activeplan']=$activeplan;
            $userdata['withdrawhistory']=$withdrawhistory;
            $userdata['totalwithdraw']=$totalwithdraw;
            $userdata['sumroi']=$sumroi;
            $userdata['sumreferral']=$sumreferral;
            $userdata['sumteamdevelopment']=$sumteamdevelopment;
            $userdata['sumdirect']=$sumdirect;
            $userdata['sumclub']=$sumclub;
            $userdata['totalincome']=$totalincome;
            /*dd($userdata);*/

            return view('control.userincomesearch')->with('userdata',$userdata);
        }
        
    }



    //Loan Remove
    public function showAdminLoanRemovePage(){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        return view('control.loanremoveadmin')->with('user',null)->with('loanamount',null);
    }

    public function findUserLoanRemove(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $datareg=$this->findUserName($request->email);
        $regex=['required','exists:users,'.$datareg['type']];
        $validator=Validator::make($request->all(),[
            'email'  =>  $regex,
        ])->validate();
        
        $user=\App\User::where('uuid', $request->email)
            ->join('user_details','users.id','=','user_details.userid')
            ->select('users.email as email','users.uuid as userid','users.usersname as name','user_details.id as uid')
            ->selectRaw('(user_details.id +'.\Session::get('logtime').') as id')
            ->first();

        $userDetail=\App\UserDetails::where('id',$user->uid)->first();
        if(is_null($userDetail->userLoanStatus())){
            return redirect()->back()->with('warning','User Dont have any loan amount.');
        } 
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining<$userDetail->userLoanStatus()->amount){
            return redirect()->back()->with('warning','User loan remaining less than loan amount');
        }  
        $loanamount=$userDetail->userLoanStatus()->amount;

        return view('control.loanremoveadmin')->with('user',$user)->with('loanamount',$loanamount);
    }

    public function removeLoanOfUser(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $validator=Validator::make($request->all(),[
            'honeypotu' =>['required','numeric'],
            'name'  =>  ['required','string'],
            'uuid'  =>  ['required','string','exists:users'],
            'amount'    =>  ['required','numeric'],
            'password'  =>  ['required']
        ]);
        if($validator->fails()){
            return redirect()->to('/Main/AdminLoanRemove')->with('errors',$validator->errors());
        }
        $adminDetails=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(\Hash::check($request->password, $adminDetails->user()->password)){
            $user=\App\User::where('uuid',$request->uuid)
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id  as id')
            ->first();

            $userDetail=\App\UserDetails::where('id',$user->id)->first();
            if(is_null($userDetail->userLoanStatus())){
                return redirect()->back()->with('warning','User Dont have any loan amount.');
            } 
            if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining<$userDetail->userLoanStatus()->amount){
                return redirect()->back()->with('warning','User loan remaining less than loan amount');
            }  

            if($user->id==($request->honeypotu-\Session::get('logtime'))){
                \DB::beginTransaction();
                try{
                    $loanid=DB::table('loan_details')->where([['loan_details.userid',$userDetail->id],['status',1]])->first();
                    $walletid=DB::table('wallet_transfers')->where([['userid',$userDetail->id],['txnid',$loanid->id],['fromWallet','loan'],['toWallet','basic']])->first();
                    $stackingid=DB::table('stacking_deposites')->where([['userid',$userDetail->id],['txnid',$walletid->id],['staketype',2]])->first();
                    $bonusid=DB::table('bonus_rewards')->where([['fromuser',$userDetail->id],['txnid',$stackingid->id],['status',3]])->first();

                    if (!is_null($bonusid)) {
                        $deletebonus=DB::table('bonus_rewards')->where('id',$bonusid->id)->delete();
                    }
                    if (!is_null($stackingid)) {
                        $deletestaking=DB::table('stacking_deposites')->where('id',$stackingid->id)->delete();
                    }
                    if (!is_null($walletid)) {
                        $deleteloanwallet=DB::table('wallet_transfers')->where('id',$walletid->id)->delete();
                    }
                    if (!is_null($loanid)) {
                        $deleteloantxn=DB::table('loan_transactions')->where('loanid',$loanid->id)->delete();
                        $deleteloan=DB::table('loan_details')->where('id',$loanid->id)->delete();
                    }
                    

                    \DB::commit();
                    return redirect()->to('/Main/AdminLoanRemove')->with('success','Loan Successfully Removed.');
                }
                catch(Exception $e){
                    \DB::rollback();
                    \Log::info('Error for User '.$userDetail->id. ' Error message is '.$e->getMessage());
                    return redirect()->back()->with('warning','You have some issue with Loan Removal. Please Try again.');
                }

            }else{
                return redirect()->to('/Main/AdminLoanRemove')->with('warning','There is some error . Please Try again.');
            }
        }
        return redirect()->back()->with('warning','Password did not match.'); 
    }




    //Wallet Reduce
    public function adminReduceWalletPage(){
        if(Session::get('user.licence')!=3){
            return redirect()->back()->with('warning','Something went wrong');
        }
        return view('control.userwalletreduce')->with('user',null)->with('balance',null);
    }
    public function findUserReduceWallet(Request $request){
        if(Session::get('user.licence')!=3){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $datareg=$this->findUserName($request->email);
        $regex=['required','exists:users,'.$datareg['type']];
        $validator=Validator::make($request->all(),[
            'email'  =>  $regex,
        ])->validate();
        
        $user=\App\User::where('uuid', $request->email)
                ->join('user_details','users.id','=','user_details.userid')
                ->select('users.email as email','users.uuid as userid','users.usersname as name','user_details.id as userrid')
                ->selectRaw('(user_details.id +'.\Session::get('logtime').') as id')
                ->first();
        $availbleStfc=\App\AccountDeposit::where('userid',$user->userrid)->first();

        if(is_null($availbleStfc)){
                return redirect()->back()->with('warning','User Dont have any wallet amount.');
        } 
        return view('control.userwalletreduce')->with('user',$user)->with('balance',$availbleStfc);
    }

    public function reduceUsdtByAdmin(Request $request){
        if(Session::get('user.licence')!=3){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $validator=Validator::make($request->all(),[
            'honeypotu' =>['required','numeric'],
            'uuid'  =>  ['required','string','exists:users'],
            'amount'    =>  ['required','numeric'],
            'password'  =>  ['required']
        ]);
        if($validator->fails()){
            return redirect()->to('/Main/ReduceUserWallet')->with('errors',$validator->errors());
        }
        $adminDetails=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(\Hash::check($request->password, $adminDetails->user()->password)){
            $user=\App\User::where('uuid',$request->uuid)
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id  as id')
            ->first();
            $availbleAmount=\App\AccountDeposit::where('userid',$user->id)->first();

            $getIncomingFund=\App\WalletTransfer::where([['userid',$user->id],/*['txnid','!=',0],*/['toWallet','wallet']])->get();
            $getOutgoingFund=\App\WalletTransfer::where([['fromUser',$user->id],['txnid',0],['fromWallet','wallet']])->get();
            $totalAmount=$getIncomingFund->sum('amount')-$getOutgoingFund->sum('amount');

            if (($request->amount > Crypt::decrypt($availbleAmount->amount)) || $request->amount > $totalAmount) {
                return redirect()->to('/Main/ReduceUserWallet')->with('warning','Please Input lower amount');
            }
            if($user->id==($request->honeypotu-\Session::get('logtime'))){
                \DB::beginTransaction();
                try{
                    $profileStore=\App\ProfileStore::all()->first();
                
                    $walletReduceEntry=\App\AccountDeposit::where('userid',$user->id)->update([
                        'amount'  =>  (Crypt::encrypt(Crypt::decrypt($availbleAmount->amount)-$request->amount)),
                    ]);
                    $walletTransferEntry=\App\WalletTransfer::insertGetId([
                      'userid'  =>  \Session::get('user.id'),
                      'txnid'  =>  0,
                      'fromWallet'  =>  'wallet',
                      'toWallet'  =>  'wallet',
                      'amount'  =>  $request->amount,
                      'fromUser'  => $user->id,
                      'release_date'    => date('Y-m-d'),
                      'created_at'  =>  date('Y-m-d H:i:s'),
                    ]);
                   /* $adminbalanceEntry=\App\AdminBalanceReduce::insertGetId([
                      'userid'  =>  $user->id,
                      'amount'  =>  $request->amount,
                      'created_at'  =>  date('Y-m-d H:i:s'),
                    ]);*/

                    \DB::commit();
                    return redirect()->to('/Main/ReduceUserWallet')->with('success','Wallet Successfully Reduced.');
                }
                catch(Exception $e){
                    \DB::rollback();
                    \Log::info('Error for User '.$user->id. ' Error message is '.$e->getMessage());
                    return redirect()->back()->with('warning','You have some issue with Wallet Reduce. Please Try again.');
                }
                
            }else{
                return redirect()->to('/Main/ReduceUserWallet')->with('warning','There is some error . Please Try again.');
            }
        }
        return redirect()->back()->with('warning','Password did not match.'); 
    }

    public function reportAdminReduceWallet(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $topupuser=DB::table('wallet_transfers')->where([['fromWallet','wallet'],['toWallet','wallet'],['wallet_transfers.userid',1]])
        /*->whereBetween('admin_balance_reduces.created_at',[$fromDate,$toDate])*/
        ->join('user_details','wallet_transfers.fromUser','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->select('users.email', 'users.uuid as userid', 'users.usersname', 'wallet_transfers.amount', 'wallet_transfers.created_at')
        ->orderByRaw('wallet_transfers.id DESC')
        ->get();
        /*dd($topupuser);*/
        return view('control.userwalletreducereport')->with('reduce',$topupuser);
    }



    //Income Reduce
    public function adminReduceIncomePage(){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        return view('control.userincomereduce')->with('user',null)->with('income',null)->with('price',0);
    }

    public function findUserReduceIncome(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $datareg=$this->findUserName($request->email);
        $regex=['required','exists:users,'.$datareg['type']];
        $validator=Validator::make($request->all(),[
            'email'  =>  $regex,
        ])->validate();
        
        $user=\App\User::where('uuid', $request->email)
                ->join('user_details','users.id','=','user_details.userid')
                ->select('users.email as email','users.uuid as userid','users.usersname as name','user_details.id as userrid')
                ->selectRaw('(user_details.id +'.\Session::get('logtime').') as id')
                ->first();
        $userDetail=\App\UserDetails::where('id',$user->userrid)->first();
        //dd($userDetail->remainingIncome());
        /*if($userDetail->remainingIncome()<=0){
                return redirect()->back()->with('warning','User Dont have any income amount.');
        }*/ 
        $price=\App\ProfileStore::where('id',1)->first()->price;
        return view('control.userincomereduce')->with('user',$user)->with('income',$userDetail)->with('price',$price);
    }

    public function adminReduceUserIncome(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $validator=Validator::make($request->all(),[
            'honeypotu' =>['required','numeric'],
            'uuid'  =>  ['required','string','exists:users'],
            'amount'    =>  ['nullable','numeric'],
            'amountusdt'    =>  ['required','numeric'],
            'password'  =>  ['required']
        ]);
        if($validator->fails()){
            return redirect()->to('/Main/ReduceUserIncome')->with('errors',$validator->errors());
        }
        $adminDetails=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(\Hash::check($request->password, $adminDetails->user()->password)){
            $user=\App\User::where('uuid',$request->uuid)
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id  as id')
            ->first();
            $userDetail=\App\UserDetails::where('id',$user->id)->first();

            /*if ( $request->amountusdt > $userDetail->remainingIncome()) {
                return redirect()->to('/Main/ReduceUserIncome')->with('warning','Please Input lower amount');
            }*/
            if($user->id==($request->honeypotu-\Session::get('logtime'))){
                if ($userDetail->remainingIncome()>=$request->amountusdt || 1==1) {
                    $reducePool=0;
                    $reduceCps=0;
                    $reduceLevel=0;
                    $reduceReward=0;
                    \DB::beginTransaction();
                    try{
                        $profile=\App\ProfileStore::all()->first();
                        $request->amount=$request->amountusdt/$profile->price;

                        $insTxn=\App\TransactionDetail::insertGetId([
                            'userid'  =>  $user->id,
                            'txntype'  =>  1,
                            'amountsftc'  =>  ($request->amount),
                            'amountusdt'  =>  ($request->amountusdt),
                            'remaining'  => ($request->amountusdt),
                            'paymentstatus'  =>  2,
                            'txndesc'  =>  'Withdrawal',
                            'currency'  =>  'usdtbep20',
                            'comments'  =>  'usdtbep20',
                            'paidby'  =>  1,
                            'release_date'  =>  date('Y-m-d'),
                            'deduction' =>  ($request->amountusdt*.05),/*($amount*.05)*/
                            'net_amount'    =>  ($request->amountusdt*.95),/*($amount*.95)*/
                            'planid'    =>  1,
                            'plan_status'  =>  0,
                            'created_at'  =>  date('Y-m-d H:i:s'),
                        ]);
                        $transactionInfo=\App\TransactionInfo::create([
                            'txnid'  =>  $insTxn,
                            'payment_addr'  =>  'Admin Reduce',
                            'payee_addr'  =>  '',
                            'transaction_hash'  =>  'Admin Reduce',
                            'created_at'  =>  date('Y-m-d H:i:s'),
                        ]);

                        $amt=$request->amountusdt;\Log::info('starting amt  '.$amt);
                        $userDetail=\App\UserDetails::where('id',$user->id)->first();
                        //reduce cps first
                        $entry=$userDetail->stackingIncome()->where('remaining_usdt','>',0)->where('status','!=',3);
                        $reduceCps=($amt>$entry->sum('remaining_usdt'))? $entry->sum('remaining_usdt'): $amt;\Log::info('reduce cps '.$reduceCps);
                        foreach($entry as $cps){ 
                            if($amt>0){
                                $updCps=\App\CpsIncome::where('id',$cps->id)->update([
                                    'remaining_usdt'    => (($amt>$cps->remaining_usdt)? 0 :($cps->remaining_usdt-$amt)),
                                    ($cps->intxna==0)?'intxna':'intxnb' =>  $insTxn
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
                                         ($level->intxna==0)?'intxna':'intxnb' =>  $insTxn
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
                                        ($bonus->intxna==0)?'intxna':'intxnb' =>  $insTxn
                                    ]);
                                    $amt-=$bonus->remaining_usdt;
                                }else{
                                    break;
                                }
                            }\Log::info('remaining after bonus '.$amt);
                        }
                               
                        if($amt>0){
                            $entry=$userDetail->clubIncome()->where('remaining_usdt','>',0)->where('status','!=',3);
                            $reducePool=($amt>$entry->sum('remaining_usdt'))? $entry->sum('remaining_usdt'):$amt;\Log::info('reduce club Reward '.$reducePool);
                            foreach($entry as $club){
                                if($amt>0){
                                    $updRef=\App\ClubIncome::where('id',$club->id)->update([
                                        'remaining_usdt'    => (($amt>$club->remaining_usdt)? 0 :($club->remaining_usdt-$amt)),
                                        ($club->intxna==0)?'intxna':'intxnb' =>  $insTxn
                                    ]);
                                    $amt-=$club->remaining_usdt;
                                }else{
                                    break;
                                }
                            }\Log::info('remaining after club '.$amt);
                        }

                        if($amt>0){
                            $insIncomeEntry=\App\CpsIncome::create([
                                'userid'        =>  $userDetail->id,
                                'txnid'         =>  $userDetail->stackingDeposite()->first()->id,
                                'amount'        =>  0,
                                'remaining'     =>  -($amt/$profile->price),
                                'amt_usdt'      =>  0,
                                'remaining_usdt'      =>  -($amt),
                                'status'        =>  0,
                                'created_at'    =>  now(),
                            ]);
                        }

                        $withdrawInfoInsert=\App\WithdrawInfo::create([
                                'txnid'  =>  $insTxn,
                                'stacking'  =>  $reduceCps,
                                'level'  =>  $reduceLevel,
                                'bonus' =>  $reduceReward,
                                'club' =>  $reducePool,
                            ]);

                        \DB::commit();
                        return redirect()->to('/Main/ReduceUserIncome')->with('success','Income Successfully Reduced.');

                        /*if($amt>0){
                            \DB::rollback();
                            \Log::info('Error for User '.$user->id. ' Error message is income less than withdraw.');
                            return redirect()->back()->with('warning','You have some issue with withdraw. Please contact to admin.');
                        }
                        else{
                            $withdrawInfoInsert=\App\WithdrawInfo::create([
                                'txnid'  =>  $insTxn,
                                'stacking'  =>  $reduceCps,
                                'level'  =>  $reduceLevel,
                                'bonus' =>  $reduceReward,
                                'club' =>  $reducePool,
                            ]);

                        \DB::commit();
                        return redirect()->to('/Main/ReduceUserIncome')->with('success','Income Successfully Reduced.');
                        }*/

                    }
                    catch(Exception $e){
                        \DB::rollback();
                        \Log::info('Error for User '.$user->id. ' Error message is '.$e->getMessage());
                        return redirect()->back()->with('warning','You have some issue with Income Reduce. Please Try again.');
                    }
                }
                else{
                    return redirect()->back()->with('warning','User do not have enough income to Reduce.');
                }   
                
            }else{
                return redirect()->to('/Main/ReduceUserIncome')->with('warning','There is some error . Please Try again.');
            }
        }
        return redirect()->back()->with('warning','Password did not match.'); 
    }


    public function userIncomeReduceHistory(Request $request){
        if($request->method()==="GET"){
        $wdreport=DB::table('transaction_details')->where([['txntype',1],['transaction_details.paymentstatus',2],['transaction_infos.payment_addr','Admin Reduce']])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select('users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" end as statusclass')
        ->orderBy('transaction_details.id', 'desc')
        ->get();
        $wdtotal=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',2],['transaction_infos.payment_addr','Admin Reduce']])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(amountsftc) as amountsftc,sum(amountusdt) as amountusdt'))
        ->get()->first();
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        $wdreport=DB::table('transaction_details')->where([['txntype',1],['transaction_details.paymentstatus',2],['transaction_infos.payment_addr','Admin Reduce']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select('users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'currency as currency', DB::raw('DATE_FORMAT(transaction_details.created_at ,"%Y-%m-%d")as txndate'))
        ->selectRaw('case when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" end as statusclass')
        ->orderBy('transaction_details.id', 'desc')
        ->get();
        $wdtotal=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',2],['transaction_infos.payment_addr','Admin Reduce']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(amountsftc) as amountsftc,sum(amountusdt) as amountusdt'))
        ->get()->first();
        }
        //dd($wdreport,$wdtotal);
        
        return view('control.userincomereducehistory')->with('requests',$wdreport)->with('sumtotal',$wdtotal);
    }







}
