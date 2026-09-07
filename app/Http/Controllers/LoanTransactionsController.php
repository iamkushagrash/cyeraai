<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\UserDetails;
use DB;
use Session;
use App\Http\Controllers\SupportQueryController;

class LoanTransactionsController extends Controller
{
    //Admin
    public function UserLoanReport(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $loanreport=DB::table('loan_details')
        ->whereBetween('loan_details.created_at',[$fromDate,$toDate])
        ->join('user_details','loan_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->select('loan_details.id as id', 'users.uuid as userid', 'users.email', 'users.usersname as name', 'loan_details.amount', 'loan_details.remaining', 'loan_details.created_at')
        ->selectRaw('case when loan_details.status=0 then "Paid" when loan_details.status=1 then "Pending" end as status')
        ->selectRaw('case when loan_details.status=0 then "status-complete" when loan_details.status=1 then "status-pending" end as statusclass')
        ->orderByRaw('loan_details.id DESC')
        ->get();
        /*dd($loanreport);*/
        return view('control.loanreport')->with('loanreport',$loanreport);
    }
    public function loanRepaymentReport(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $loanreport=DB::table('loan_transactions')->where('loan_transactions.txntype',1)
        ->whereBetween('loan_transactions.created_at',[$fromDate,$toDate])
        ->join('loan_details','loan_transactions.loanid','=','loan_details.id')
        ->join('user_details','loan_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->select('loan_details.id as id', 'users.uuid as userid', 'users.email', 'users.usersname as name', 'loan_details.amount as loan', 'loan_details.remaining', 'loan_transactions.amount', 'loan_transactions.created_at')
        ->selectRaw('case when loan_details.status=0 then "Paid" when loan_details.status=1 then "Pending" end as status')
        ->selectRaw('case when loan_details.status=0 then "status-complete" when loan_details.status=1 then "status-pending" end as statusclass')
        ->orderByRaw('loan_transactions.id DESC')
        ->get();
        /*dd($loanreport);*/
        return view('control.loanrepaymentreport')->with('loanreport',$loanreport);
    }



    public function searchUserforOneClick(){
        return view('control.useroneclick')->with('userdata',array());
    }

    public function searchUserbyUserIdOneClick(Request $request){
        $datareg=$this->findUserName($request->userrid);
        $showdata=DB::table('users')->where([['licence','1'],['uuid',$request->userrid]])
        ->select('users.id as id')
        ->get()->first();
        if (is_null($showdata)) {
            return redirect()->back()->with(['warning'=>'User Not Found']);
        }
        else{
            return redirect('/Main/UserOneClick/'.$request->userrid);
        }
        
    }

    public function searchUserOneClick($userid, Request $request){
        $datareg=$this->findUserName($userid);
        $user=DB::table('users')->where([['licence','1'],['uuid',$userid]])
        ->join('user_details','users.id','=','user_details.userid')
        ->select('users.id as id','users.email as email','users.uuid as userid','users.usersname as name','user_details.id as uid')
        ->first();

        if (is_null($user)) {
            return redirect()->back()->with(['warning'=>'User Not Found']);
        }
        else{
            $userDetail=\App\UserDetails::where('id',$user->uid)->first();

            $memberedit=DB::table('users')->where([['users.licence','1'],['users.uuid',$userid]])
            ->join('user_details','users.id','=','user_details.userid')
            ->join('users as gd','gd.id','=','user_details.sponsorid')
            ->leftJoin('asset_details','asset_details.userid','=','user_details.id')
            ->select('users.id as id', 'users.usersname as usersname', 'users.uuid as uuid', 'users.s_password as showpassword', 'users.ccode as ccode', 'users.email as email', 'users.contact as contact', 'users.permission as permission', 'user_details.leveluser as levelpercentage', 'gd.uuid as guiderid', 'gd.usersname as guidername', 'asset_details.bep20addr as bep20address', 'asset_details.usdttrc20addr as usdtaddress', 'asset_details.usdtbep20addr as usdtbep20address','user_details.total_direct_investment as directbusiness','user_details.total_level_investment as levelbusiness')
            ->selectRaw('case when asset_details.asset_status=0 then "Not Open" when asset_details.asset_status=1 then "Open" end as asset_status')
            ->selectRaw('case when user_details.roi_status=0 then "Not Open" when user_details.roi_status=1 then "Open" end as roi_status')
            ->selectRaw('case when user_details.booster=1 then "Inactive" when user_details.booster=2 then "Active" end as booster_status')
            ->selectRaw('case when user_details.power_protected=0 then "Not Open" when user_details.power_protected=1 then "Open" end as power_status')
            ->selectRaw('case when user_details.lifetime_protected=0 then "Not Open" when user_details.lifetime_protected=1 then "Open" end as lifetime_status')
            ->selectRaw('case when user_details.silver_protected=0 then "Not Open" when user_details.silver_protected=1 then "Open" end as silver_status')
            ->get()->first();

            $totalroireceived=DB::table('cps_incomes')->where('cps_incomes.userid',$user->uid)
            ->join('stacking_deposites','stacking_deposites.id','=','cps_incomes.txnid')
            ->select('cps_incomes.amount as amount','cps_incomes.amt_usdt as amountusdt', 'stacking_deposites.amount as principal', 'stacking_deposites.usdt as principalusdt','cps_incomes.created_at')
            /*->selectRaw('DATE_FORMAT(cps_incomes.created_at,"%d-%m-%Y") as created_at')*/
            ->selectRaw('case when cps_incomes.status=0 then "Credit" when cps_incomes.status=1 then "Withdraw" when cps_incomes.status=3 then "Locked" end as status')
            ->selectRaw('case when cps_incomes.status=0 then "ffffffbf " when cps_incomes.status=1 then "ffffffbf " when cps_incomes.status=3 then "FF0000" end as statusclass')
            ->orderBy('cps_incomes.id', 'desc')
            ->get();
            $totaldirectreceived=DB::table('bonus_rewards')->where([['bonus_rewards.userid',$user->uid],['bonus_rewards.description','referral']])
            ->join('user_details','user_details.id','=','bonus_rewards.fromuser')
            ->join('users','users.id','=','user_details.userid')
            ->select('bonus_rewards.amount as amount','bonus_rewards.amt_usdt as amountusdt','bonus_rewards.description as level','users.uuid as fromid','users.usersname as fromname','bonus_rewards.created_at')
            /*->selectRaw('DATE_FORMAT(bonus_rewards.created_at,"%d-%m-%Y") as created_at')*/
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
            ->select('club_incomes.amount as amount', 'club_details.clubname as clubname','club_incomes.created_at')
            /*->selectRaw('DATE_FORMAT(club_incomes.created_at,"%d-%m-%Y") as created_at')*/
            ->selectRaw('case when club_incomes.status=0 then "Credit" when club_incomes.status=1 then "Withdraw" when club_incomes.status=3 then "Locked" end as status')
            ->selectRaw('case when club_incomes.status=0 then "ffffffbf " when club_incomes.status=1 then "ffffffbf " when club_incomes.status=3 then "FF0000" end as statusclass')
            ->orderBy('club_incomes.id', 'desc')
            ->get();
            $totalrewardreceived=DB::table('achievement_incomes')->where('achievement_incomes.userid',$user->uid)
            ->join('achievement_details','achievement_details.id','=','achievement_incomes.achievementid')
            ->select('achievement_incomes.amount as amount', 'achievement_details.rewardname as rewardname','achievement_incomes.created_at')
            /*->selectRaw('DATE_FORMAT(achievement_incomes.created_at,"%d-%m-%Y") as created_at')*/
            ->selectRaw('case when achievement_incomes.status=0 then "Credit" when achievement_incomes.status=1 then "Withdraw" when achievement_incomes.status=3 then "Locked" end as status')
            ->selectRaw('case when achievement_incomes.status=0 then "ffffffbf " when achievement_incomes.status=1 then "ffffffbf " when achievement_incomes.status=3 then "FF0000" end as statusclass')
            ->orderBy('achievement_incomes.id', 'desc')
            ->get();
            $withdrawhistory=DB::table('transaction_details')->where([['transaction_details.userid',$user->uid],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal']/*,['transaction_details.paymentstatus',2]*/])
            ->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
            ->join('user_details','transaction_details.userid','=','user_details.id')
            ->join('users','user_details.userid','=','users.id')
            ->select('transaction_details.amountsftc','transaction_details.amountusdt','transaction_details.deduction','transaction_details.net_amount','transaction_details.currency', 'users.uuid', 'users.email', 'users.usersname', 'transaction_infos.transaction_hash','transaction_details.created_at')
            /*->selectRaw('DATE_FORMAT(transaction_details.created_at,"%d-%m-%Y") as created_at')*/
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
            ->select('stacking_deposites.amount','stacking_deposites.usdt','stacking_details.cps', 'users.email', 'users.uuid as userid', 'users.usersname as usersname', 'u.usersname as fromname', 'u.uuid as fromid','stacking_deposites.created_at')
            /*->selectRaw('DATE_FORMAT(stacking_deposites.created_at,"%d-%m-%Y") as created_at')*/
            ->selectRaw('case when stacking_deposites.status=-1 then "Withdraw" when stacking_deposites.status=0 then "Closed" when stacking_deposites.status=1 then "Active" when stacking_deposites.status=2 then "Active" when stacking_deposites.status=3 then "Active" end as status')
            ->selectRaw('case when stacking_deposites.status=0 then "status-cancelled" when stacking_deposites.status=1 then "status-complete" when stacking_deposites.status=2 then "status-complete" when stacking_deposites.status=3 then "status-complete" end as statusclass')
            ->selectRaw('case when stacking_deposites.staketype=1 then "Wallet" when stacking_deposites.staketype=2 then "Loan" when stacking_deposites.staketype=3 then "Gold" when stacking_deposites.staketype=4 then "Silver" end as staketype')
            ->orderByRaw('stacking_deposites.id DESC')
            ->get();

            $totalwithdraw=DB::table('transaction_details')->where([['transaction_details.userid',$user->uid],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',2]])
            //->select(DB::raw('sum(amountsftc) as amountcoin,sum(amountusdt) as amountusdt'))
            ->selectRaw('SUM(amountsftc) as amountcoin,SUM(amountusdt) as amountusdt')
            ->first();
            $sumroi=DB::table('cps_incomes')->where('cps_incomes.userid',$user->uid)
            //->select(DB::raw('sum(amt_usdt) as totalroi,sum(remaining_usdt) as remainingroi'))
            ->selectRaw('SUM(amt_usdt) as totalroi,SUM(remaining_usdt) as remainingroi')
            ->first();
            $sumreferral=DB::table('level_incomes')->where([['level_incomes.userid',$user->uid],['level_incomes.description','l']])
            //->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
            ->selectRaw('SUM(amt_usdt) as totallevel,SUM(remaining_usdt) as remaininglevel')
            ->first();
            $sumteamdevelopment=DB::table('level_incomes')->where([['level_incomes.userid',$user->uid],['level_incomes.description','r']])
            //->select(DB::raw('sum(amt_usdt) as totaltd,sum(remaining_usdt) as remainingtd'))
            ->selectRaw('SUM(amt_usdt) as totaltd,SUM(remaining_usdt) as remainingtd')
            ->first();
            $sumdirect=DB::table('bonus_rewards')->where([['bonus_rewards.userid',$user->uid],['bonus_rewards.description','referral']])
            //->select(DB::raw('sum(amt_usdt) as totaldirect,sum(remaining_usdt) as remainingdirect'))
            ->selectRaw('SUM(amt_usdt) as totaldirect,SUM(remaining_usdt) as remainingdirect')
            ->first();
            $sumclub=DB::table('club_incomes')->where([['club_incomes.userid',$user->uid]/*,['rank_incomes.comment','rank']*/])
            //->select(DB::raw('sum(amt_usdt) as totalclub,sum(remaining_usdt) as remainingclub'))
            ->selectRaw('SUM(amt_usdt) as totalclub,SUM(remaining_usdt) as remainingclub')
            ->first();
            $sumachievement=DB::table('achievement_incomes')->where([['achievement_incomes.userid',$user->uid]])
            //->select(DB::raw('sum(amount) as totalachievement,sum(remaining) as remainingachievement'))
            ->selectRaw('SUM(amount) as totalachievement,SUM(remaining) as remainingachievement')
            ->first();
            $totalincome=$sumroi->totalroi+$sumreferral->totallevel+$sumteamdevelopment->totaltd+$sumdirect->totaldirect+$sumclub->totalclub+$sumachievement->totalachievement;

            $userlevel=$userDetail->levelStatus();
            $adminlevel=$userDetail->leveluser;
            if ($userlevel>=$adminlevel) {
                $level=$userlevel;
            }else{
                $level=$adminlevel;
            }
            
        
            $userdata['user']=$user;
            $userdata['userDetail']=$userDetail;
            $userdata['editdata']=$memberedit;
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
            $userdata['sumachievement']=$sumachievement;
            $userdata['totalincome']=$totalincome;
            $userdata['level']=$level;
            /*dd($userdata);*/

            return view('control.useroneclick')->with('userdata',$userdata);
        }   
    }


    

        public function MemberUpdateOneClick(Request $request)
        {
            Validator::make($request->all(), [
                'name'      => ['required', 'string','regex:/^[a-zA-Z0-9\s]|[^<>]+$/u'],
                'uuid'      => ['required', 'string','regex:/^CAI|cai|Cai[0-9]{7}+$/'],
                'contact'      => ['nullable', 'numeric'],
                'oldemail' => ['nullable', 'string', 'email'],
                'email' => ['required', 'string', 'email'/*, 'unique:users'*/],
                'bep20address'      => ['string','nullable','regex:/^0x[a-fA-F0-9]{40}$/u'],
                'usdtaddress'      => ['string','nullable','regex:/^T[a-zA-Z0-9]{33}$/u'],
                'usdtbep20address'      => ['string','nullable','regex:/^0x[a-fA-F0-9]{40}$/u'],
            ])->validate();

            $userdt=DB::table('user_details')->where([['users.licence','1'],['users.uuid',$request->uuid]])
            ->join('users','users.id','=','user_details.userid')
            ->select('users.id as id', 'user_details.id as uid')
            ->get()->first();
            $newmailcheck=\App\User::where('email',$request->email)->first();
            
            /*if (!is_null($newmailcheck) && $request->oldemail!=$request->email) {
                return redirect('/Main/User/'.$request->uuid)->with('warning','Email Already Exists. Please use a different email.');
            }*/
            $updusr=DB::table('users')->where('id',$userdt->id)->update([
                'usersname' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                ]);
            
            $useredit=\App\AssetDetail::firstOrNew(array('userid'=>$userdt->uid));
            if(!is_null($request->bep20address))
                $useredit->bep20addr=$request->bep20address;
            if(!is_null($request->usdtaddress))
                $useredit->usdttrc20addr=$request->usdtaddress;
            if(!is_null($request->usdtbep20address))
                $useredit->usdtbep20addr=$request->usdtbep20address;
            $useredit->save();

            $details['uniqueid']=$request->uuid;
            $details['name']=$request->name;
            $details['contact']=$request->contact;
            $details['email']=$request->email;
        
            return redirect('/Main/UserOneClick/'.$request->uuid)/*->back()*/->with('success','Profile Successfully Edited');
        }



    public function searchUserforMWData(){
        return view('control.usersendmw')->with('userdata',array());
    }

    public function searchUserbyMWData(Request $request){
        $datareg=$this->findUserName($request->userrid);
        $showdata=DB::table('users')->where([['licence','1'],['uuid',$request->userrid]])
        ->select('users.id as id','users.email as email','users.usersname as usersname','users.uuid as uuid','users.contact as contact','users.s_password as s_password')
        ->get()->first();
        if (is_null($showdata)) {
            return redirect()->back()->with(['warning'=>'User Not Found']);
        }
        else{
            return redirect('/Main/SearchUserIdforMW')->with('success','User details verified successfully.');
        }
        
    }









}
