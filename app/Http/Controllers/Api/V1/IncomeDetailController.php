<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use DB;

class IncomeDetailController extends BaseController
{
    //USer
    public function userDepositHistory(Request $request)
    {
        $detail['deposithistory']=DB::table('transaction_details')->where([['transaction_details.userid',$request->user()->userDetails()->first()->id],['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite']/*,['transaction_details.paymentstatus',2]*/])
        ->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
        ->join('user_details','transaction_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->select(/*'transaction_details.amountsftc as bfi_amount',*/'transaction_details.amountusdt as usdt_amount','transaction_details.currency',/* 'users.email', 'users.usersname',*/ 'transaction_infos.transaction_hash')
        ->selectRaw('DATE_FORMAT(transaction_details.created_at,"%d-%m-%Y") as created_at')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Failed" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Failed" end as status')
        ->orderByRaw('transaction_details.id DESC')
        ->get();

        return $this->sendResponse($detail,'Deposit History');
    }

    public function userMyPackages(Request $request){
        
        $data['myPackage']=DB::table('stacking_deposites')->where('stacking_deposites.userid',$request->user()->userDetails()->first()->id)
        ->join('user_details','stacking_deposites.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->join('stacking_details','stacking_deposites.planid','=','stacking_details.id')
        ->select('stacking_deposites.amount','stacking_deposites.usdt','stacking_details.cps', 'users.email', 'users.uuid as userid', 'users.usersname as usersname')
        ->selectRaw('DATE_FORMAT(stacking_deposites.created_at,"%d-%m-%Y") as created_at')
        ->selectRaw('case when stacking_deposites.status=2 then "Withdraw" when stacking_deposites.status=0 then "Closed" when stacking_deposites.status=1 then "Active" when stacking_deposites.status=3 then "Active" end as status')
        ->selectRaw('case when stacking_deposites.status=0 then "status-cancelled" when stacking_deposites.status=1 then "status-complete" when stacking_deposites.status=2 then "status-cancelled" when stacking_deposites.status=3 then "status-complete" end as statusclass')
        ->orderByRaw('stacking_deposites.id DESC')
        ->get();
        foreach($data['myPackage'] as $d){
            $d->email=$this->maskedEmail($d->email);
        }
        
        return $this->sendResponse($data,'User Packages');
    }

    public function userTransactionHistory(Request $request){
        $data['transactionHistory']=DB::table('stacking_deposites')->where([['wallet_transfers.fromUser',$request->user()->userDetails()->first()->id],['wallet_transfers.fromWallet','wallet'],['wallet_transfers.toWallet','basic']])
        ->join('wallet_transfers','stacking_deposites.txnid','=','wallet_transfers.id')
        ->join('user_details','stacking_deposites.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->join('stacking_details','stacking_deposites.planid','=','stacking_details.id')
        ->select('stacking_deposites.amount','stacking_deposites.usdt','stacking_details.cps', 'users.email', 'users.uuid as userid', 'users.usersname as usersname'/*, 'wallet_transfers.userid'*/ )
        ->selectRaw('DATE_FORMAT(stacking_deposites.created_at,"%d-%m-%Y") as created_at')
        ->selectRaw('case when stacking_deposites.status=2 then "Withdraw" when stacking_deposites.status=0 then "Closed" when stacking_deposites.status=1 then "Active" when stacking_deposites.status=3 then "Active" end as status')
        ->selectRaw('case when stacking_deposites.status=0 then "status-cancelled" when stacking_deposites.status=1 then "status-complete" when stacking_deposites.status=2 then "status-cancelled" when stacking_deposites.status=3 then "status-complete" end as statusclass')
        ->orderByRaw('stacking_deposites.id DESC')
        ->get();
        foreach($data['transactionHistory'] as $d){
            $d->email=$this->maskedEmail($d->email);
        }

        return $this->sendResponse($data,'Transaction History');
    }

    public function userStakingReport(Request $request){
        if(!isset($request->fromdate)){
            /*$fromDate=date('Y-m-d 00:00:00');*/
            $fromDate=date('2022-10-01 00:00:00');
            $toDate=date('Y-m-d 23:59:59');
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $data['staking_report']=DB::table('cps_incomes')->where('cps_incomes.userid',$request->user()->userDetails()->first()->id)
        ->whereBetween('cps_incomes.created_at',[$fromDate,$toDate])
        ->join('stacking_deposites','stacking_deposites.id','=','cps_incomes.txnid')
        ->select('cps_incomes.amount as cps','cps_incomes.amt_usdt as cpsusdt', 'stacking_deposites.amount as principal', 'stacking_deposites.usdt as principalusdt')
        ->selectRaw('DATE_FORMAT(cps_incomes.created_at,"%d-%m-%Y") as created_at')
        ->selectRaw('case when cps_incomes.status=0 then "Credit" when cps_incomes.status=1 then "Withdraw" end as status')
        ->selectRaw('case when cps_incomes.status=0 then "status-pending" when cps_incomes.status=1 then "status-complete" end as statusclass')
        ->orderBy('cps_incomes.id', 'desc')
        ->get();
        return $this->sendResponse($data,'Staking Report');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userDirectReport(Request $request){
        if(!isset($request->fromdate)){
            /*$fromDate=date('Y-m-d 00:00:00');*/
            $fromDate=date('2022-10-01 00:00:00');
            $toDate=date('Y-m-d 23:59:59');
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $data['direct_referral']=DB::table('bonus_rewards')->where([['bonus_rewards.userid',$request->user()->userDetails()->first()->id],['bonus_rewards.description','referral']])
        ->whereBetween('bonus_rewards.created_at',[$fromDate,$toDate])
        ->join('user_details','user_details.id','=','bonus_rewards.fromuser')
        ->join('users','users.id','=','user_details.userid')
        ->select('bonus_rewards.amount as amount','bonus_rewards.amt_usdt as amountusdt','users.uuid as fromid','users.usersname as fromname')
        ->selectRaw('DATE_FORMAT(bonus_rewards.created_at,"%d-%m-%Y") as created_at')
        ->orderBy('bonus_rewards.id', 'desc')
        ->get();
        return $this->sendResponse($data,'Direct Referral');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userStakingReferralReport(Request $request){
        if(!isset($request->fromdate)){
            /*$fromDate=date('Y-m-d 00:00:00');*/
            $fromDate=date('2022-10-01 00:00:00');
            $toDate=date('Y-m-d 23:59:59');
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $data['staking_referral_report']=DB::table('level_incomes')->where([['level_incomes.userid',$request->user()->userDetails()->first()->id],['level_incomes.description','l']])
        ->whereBetween('level_incomes.created_at',[$fromDate,$toDate])
        ->select(DB::raw('DATE_FORMAT(level_incomes.created_at ,"%Y-%m-%d")as txndate'))
        ->selectRaw('sum(level_incomes.amount) as amount,sum(level_incomes.amt_usdt) as amountusdt')
        ->orderBy('level_incomes.id', 'desc')
        ->groupBy('txndate')
        ->get();

        $userlevel=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first()->levelStatus();
        $adminlevel=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first()->leveluser;
        if ($userlevel>=$adminlevel) {
            $data['levelpercentage']=$userlevel;
        }else{
            $data['levelpercentage']=$adminlevel;
        }
        
        return $this->sendResponse($data,'Team Generation Bonus');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userStakingReferralReportDate(Request $request, $txndate){
        $fromDate=$txndate;
        $toDate=$txndate.' 23:59:59';

        $data['staking_referral_report']=DB::table('level_incomes')->where([['level_incomes.userid',$request->user()->userDetails()->first()->id],['level_incomes.description','l']])
            ->whereBetween('level_incomes.created_at',[$fromDate,$toDate])
            ->join('user_details','user_details.id','=','level_incomes.fromuser')
            ->join('users','users.id','=','user_details.userid')
            ->select('users.usersname as fromname','users.email as fromemail','users.uuid as fromuserid','level_incomes.amount as amount','level_incomes.amt_usdt as amountusdt', DB::raw('DATE_FORMAT(level_incomes.created_at ,"%Y-%m-%d")as txndate'))
            ->orderBy('level_incomes.id', 'desc')
            ->get();

        foreach($data['staking_referral_report'] as $d){
            $d->fromemail=$this->maskedEmail($d->fromemail);
        }
        
        return $this->sendResponse($data,'Team Generation Bonus');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function usertTeamDevelopmemtReport(Request $request){
        if(!isset($request->fromdate)){
            /*$fromDate=date('Y-m-d 00:00:00');*/
            $fromDate=date('2022-10-01 00:00:00');
            $toDate=date('Y-m-d 23:59:59');
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $data['team_development_report']=DB::table('level_incomes')->where([['level_incomes.userid',$request->user()->userDetails()->first()->id],['level_incomes.description','r']])
        ->whereBetween('level_incomes.created_at',[$fromDate,$toDate])
        ->select(DB::raw('DATE_FORMAT(level_incomes.created_at ,"%Y-%m-%d")as txndate'))
        ->selectRaw('sum(level_incomes.amount) as amount,sum(level_incomes.amt_usdt) as amountusdt')
        ->orderBy('level_incomes.id', 'desc')
        ->groupBy('txndate')
        ->get();
        return $this->sendResponse($data,'Team Generation Bonus');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userTeamDevelopmemtReportDate(Request $request, $txndate){
        $fromDate=$txndate;
        $toDate=$txndate.' 23:59:59';

        $data['team_development_report']=DB::table('level_incomes')->where([['level_incomes.userid',$request->user()->userDetails()->first()->id],['level_incomes.description','r']])
            ->whereBetween('level_incomes.created_at',[$fromDate,$toDate])
            ->join('user_details','user_details.id','=','level_incomes.fromuser')
            ->join('users','users.id','=','user_details.userid')
            ->select('users.usersname as fromname','users.email as fromemail','users.uuid as fromuserid','level_incomes.amount as amount','level_incomes.amt_usdt as amountusdt', DB::raw('DATE_FORMAT(level_incomes.created_at ,"%Y-%m-%d")as txndate'))
            ->orderBy('level_incomes.id', 'desc')
            ->get();

        foreach($data['team_development_report'] as $d){
            $d->fromemail=$this->maskedEmail($d->fromemail);
        }
        
        return $this->sendResponse($data,'Team Generation Bonus');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userClubReport(Request $request){
        if(!isset($request->fromdate)){
            $fromDate=date('2022-10-01 00:00:00');
            $toDate=date('Y-m-d 23:59:59');
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $data['club_reward']=DB::table('club_incomes')->where([['club_incomes.userid',$request->user()->userDetails()->first()->id]/*,['club_incomes.txnDesc','Salary']*/])
        ->whereBetween('club_incomes.created_at',[$fromDate,$toDate])
        ->join('club_details','club_details.id','=','club_incomes.clubid')
        ->select('club_incomes.amount as amount', 'club_incomes.amt_usdt as amountusdt', 'club_details.clubname as clubname')
        ->selectRaw('DATE_FORMAT(club_incomes.created_at,"%d-%m-%Y") as created_at')
        ->orderBy('club_incomes.id', 'desc')
        ->get();

        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();

        $totalturnover=$userDetail->total_investment-$userDetail->total_self_investment;
        $userClub=\App\ClubDetails::where('business_min','<=',$totalturnover)->get()->last();
        $clubId=(!is_null($userClub) && $userDetail->clubuser>$userClub->id)?$userDetail->clubuser:((!is_null($userClub))?$userClub->id:1);
        $userClub=\App\ClubDetails::where('id',$clubId)->first();

        $data['totalrequired']=(is_null($userClub->business_min)?0:$userClub->business_min);
        $data['AchievedClub']=(!is_null($userDetail->clubBusiness()['achieved'])?$userDetail->clubBusiness()['achieved']->clubname:'Not Achieved');
        $data['NextClub']=$userDetail->clubBusiness()['next']->clubname;

        $data['powerline']=$userDetail->clubBusiness()['first'];
        $data['restline']=$userDetail->clubBusiness()['rest'];

        $data['clubQualifications'] = DB::table('club_details')
        ->select('id as clubid','clubname', 'business_min', 'business_max', 'cps_amount', 'powerline', 'remainingline')
        ->orderBy('id', 'asc')
        ->get();

        return $this->sendResponse($data,'Club Reward');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userLifetimeReport(Request $request){
        if(!isset($request->fromdate)){
            $fromDate=date('2022-10-01 00:00:00');
            $toDate=date('Y-m-d 23:59:59');
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $data['lifetime_reward']=DB::table('achievement_incomes')->where([['achievement_incomes.userid',$request->user()->userDetails()->first()->id]/*,['achievement_incomes.txnDesc','Salary']*/])
        ->whereBetween('achievement_incomes.created_at',[$fromDate,$toDate])
        ->join('achievement_details','achievement_details.id','=','achievement_incomes.achievementid')
        ->select('achievement_incomes.amount as amount', 'achievement_details.rewardname as rewardname')
        ->selectRaw('DATE_FORMAT(achievement_incomes.created_at,"%d-%m-%Y") as created_at')
        ->orderBy('achievement_incomes.id', 'desc')
        ->get();

        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();

        $data['powerline']=$userDetail->lifetimeAchievementBusiness()['first'];
        $data['secondline']=$userDetail->lifetimeAchievementBusiness()['second'];
        $data['restline']=$userDetail->lifetimeAchievementBusiness()['rest'];

        //$data['AchievedReward']=(!is_null($userDetail->lifetimeAchievementBusiness()['achieved'])?$userDetail->lifetimeAchievementBusiness()['achieved']->last()->rewardname:'Not Achieved');
        $data['nextReward']=$userDetail->lifetimeAchievementBusiness()['next'];

        $data['rewardQualifications'] = DB::table('achievement_details')
        ->select('id as rewardid','rewardname', 'business_min', 'business_max', 'cps_amount', 'firstline', 'secondline', 'remainingline')
        ->orderBy('id', 'asc')
        ->get();

        return $this->sendResponse($data,'Lifetime Reward');
        //return $this->sendError('Validation Error.', $vali->errors());
    }



    public function userWithdrawHistory(Request $request)
    {
        $detail['withdrawhistory']=DB::table('transaction_details')->where([['transaction_details.userid',$request->user()->userDetails()->first()->id],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal']/*,['transaction_details.paymentstatus',2]*/])
        ->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
        ->join('user_details','transaction_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->select('transaction_details.amountsftc as coinamount','transaction_details.amountusdt','transaction_details.deduction','transaction_details.net_amount','transaction_details.currency', 'users.uuid', 'users.email', 'users.usersname', 'transaction_infos.transaction_hash')
        ->selectRaw('DATE_FORMAT(transaction_details.created_at,"%d-%m-%Y") as created_at')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Verification Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Cancelled" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Expired" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "badge-warning" when transaction_details.paymentstatus=1 then "badge-warning" when transaction_details.paymentstatus=2 then "badge-success" when transaction_details.paymentstatus=3 then "badge-danger" when transaction_details.paymentstatus=4 then "badge-danger" when transaction_details.paymentstatus=5 then "badge-danger" end as statusclass')
        ->orderByRaw('transaction_details.id DESC')
        ->get();

        return $this->sendResponse($detail,'Withdraw History');
    }


    public function loanRepaymentHistory(Request $request){
        
        $detail['repaymenthistory']=DB::table('loan_details')->where([['loan_details.userid',$request->user()->userDetails()->first()->id],['loan_transactions.txntype',1]])
        ->join('loan_transactions','loan_transactions.loanid','=','loan_details.id')
        ->select('loan_details.remaining as remaining', 'loan_transactions.amount as amount', 'loan_transactions.created_at as created_at')
        ->orderBy('loan_transactions.id', 'desc')
        ->get();
        /*dd($reportbasic);*/
        return $this->sendResponse($detail,'Loan Repayment History');
    }


    public function checkRemainingIncomeforMetaW(Request $request){
        $allowedIps = ['127.0.0.1', '15.235.37.172','122.176.71.56','88.198.20.15'];

        $data=$request->all();
        $clientIp = $request->ip();
        /*dd($clientIp);*/
        if (!in_array($clientIp, $allowedIps)) {
            return $this->sendErrortoMetaWallet('Access Denied', ['errors' => 'IP Not Whitelisted']);
        }

        if(!isset($data['Wallet']) || is_null($data['Wallet'])){
            $data['Wallet']='income';
        }

        $datareg=$this->findUserName($data['user_id']);
        $regex=['required','exists:users,'.$datareg['type']];
        $validator=Validator::make($data, [
            'user_id' =>  $regex,
            'Token'     =>   ['required','string'], 
            'Wallet'     =>   ['required','string'],        
        ]);
        if($validator->fails()){

            return $this->sendErrortoMetaWallet('Validation Error.', $validator->errors());
        }
        if($data['Wallet']!='income'){
            return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Fetch income only']);
        }
        if($data['Token']=='meta6645dgbbg366sf3566d863'){
            $user=\App\User::where('uuid',$data['user_id'])
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id as uid','uuid as userid','usersname as name','email as email')->first();

            $userDetail=\App\UserDetails::where('id',$user->uid)->first();
            $details['success']=true;
            //$details['mwtPrice']=$price=\App\ProfileStore::where('id',1)->first()->price;
            $details['balance']=$userDetail->remainingIncome();
            //$details['balance_usdt']=($userDetail->remainingIncome()*$price);
            $details['deduction_percentage']=10;
            if(!is_null($userDetail->assetDetail())){
                $details['withdraw_status']=$userDetail->assetDetail()->asset_status;
            }else{
                $details['withdraw_status']=0;
            }

            return $this->sendResponsetoMetaWallet($details,'MetaWallet Balance');

        }else{
            return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Token Mismatch']);
        }



    }

    public function requestWithdrawForMetawallet(Request $request){
        $allowedIps = ['122.176.71.56','88.198.20.15'];

        $data=$request->all();
        $clientIp = $request->ip();
        /*dd($data);*/
        if (!in_array($clientIp, $allowedIps)) {
            return $this->sendErrortoMetaWallet('Access Denied', ['errors' => 'IP Not Whitelisted']);
        }

        $datareg=$this->findUserName($data['user_id']);
        $regex=['required','exists:users,'.$datareg['type']];
        $validator=Validator::make($data, [
            'user_id' =>  $regex,
            'Token'     =>   ['required','string'], 
            'amount'     =>   ['required','numeric'], 
            /*'amount_usdt'     =>   ['required','numeric'],*/   
            'txn_pin'     =>   ['required','numeric'],        
        ]);
        if($validator->fails()){
            return $this->sendErrortoMetaWallet('Validation Error.', $validator->errors());
        }
        if($data['Token']=='meta6645dgbbg366sf3566d863'){
            $request->amount_usdt=$data['amount'];
            $request->txn_pin=$data['txn_pin'];

            $user=\App\User::where('uuid',$data['user_id'])
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id as uid','uuid as userid','usersname as name','email as email')->first();

            $userDetail=\App\UserDetails::where('id',$user->uid)->first();
            $profile=\App\ProfileStore::where('id',1)->first();
            $request->amount=$request->amount_usdt/$profile->price;
            if(!is_null($userDetail->assetDetail()) && $userDetail->assetDetail()->asset_status==0){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Withdrawal not permitted']);
            }
            if($userDetail->user()->permission==0){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Account Blocked']);
            }
            if(fmod($request->amount_usdt, 10) != 0){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Amount should be multiple of 10$']);
            }
            $silverAmount=$userDetail->stackingDeposite()->where('staketype',4)->sum('usdt');
            if($silverAmount>0 && !in_array(date('d'), [10, 20, 30])){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Withdrawal available on 10,20,30th of every month']);
            }
            $previousWithdrawal=\App\TransactionDetail::where([['paymentstatus','<=',2],['txntype',1],['created_at','>',date('Y-m-d H:i:s',strtotime('-24 hours',strtotime(now())))],['userid',$userDetail->id]])->orderBy('id','desc')->get();
            $amountWith=($request->amount_usdt)+$previousWithdrawal->sum('amountusdt');

            if($amountWith>2000){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Maximum withdraw amount is 2000 $ and Your requested Withdrawal in last 24 hours is '.$previousWithdrawal->sum('amountusdt').' $']);
            }

            if(($userDetail->remainingIncome())>=$request->amount_usdt){
                if($request->amount_usdt<10){
                    return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Minimum withdraw amount is 10 $ and Your requested USDT equals to '.$request->amount_usdt.' $']);
                }

                $reducePool=0;
                $reduceCps=0;
                $reduceLevel=0;
                $reduceReward=0;

                \DB::beginTransaction();
                try{
                    $txnId=$this->insertWithdrawEntryMW($userDetail->id,$request->amount,$request->amount_usdt);

                    $amt=$request->amount_usdt;
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
                        \Log::info('Error for User '.$userDetail->id. ' Error message is income less than withdraw.');
                        return $this->sendError('Validation Error.', ["error"=>'You have some issue with withdraw. Please contact to admin']);
                    }else{
                        $withdrawInfoInsert=\App\WithdrawInfo::create([
                            'txnid'  =>  $txnId,
                            'stacking'  =>  $reduceCps,
                            'level'  =>  $reduceLevel,
                            'bonus' =>  $reduceReward,
                            'club' =>  $reducePool,
                        ]);
                        
                    }

                }
                catch(Exception $e){
                    \DB::rollback();
                    \Log::info('Error for User '.$userDetail->id. ' Error message is '.$e->getMessage());
                    return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'You have some issue with withdraw. Please Try again.']);
                }
                \DB::commit();
                /*return $this->sendResponse('Your CAI Sell of amount $ '.$request->amount_usdt.' placed successfully.');*/
                $details['success']=true;
                $details['msg']='Withdraw Successfully';
                $details['Status']=2;
                $details['txn_pin']=$request->txn_pin;
                $details['net_balance']=($request->amount_usdt*.90);
                return $this->sendResponsetoMetaWallet($details,'Withdraw Successfully');

            }
            else{
                return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Withdrawal amount greater than balance']);
            }
            
        }else{
            return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Token Mismatch']);
        }



    }

    public function insertWithdrawEntryMW($userId,$styamount,$amount){
        $insTxn=\App\TransactionDetail::insertGetId([
            'userid'  =>  $userId,
            'txntype'  =>  1,
            'amountsftc'  =>  ($styamount),
            'amountusdt'  =>  ($amount),
            'remaining'  => 0,
            'paymentstatus'  =>  2,
            'txndesc'  =>  'Withdrawal',
            'currency'  =>  'usdt',
            'comments'  =>  'usdt',
            'paidby'  =>  0,
            'release_date'  =>  date('Y-m-d'),
            'deduction' =>  ($amount*.10),/*($amount*.05)*/
            'net_amount'    =>  ($amount*.90),/*($amount*.95)*/
            'planid'    =>  0,
            'plan_status'  =>  0,
            'paidby'  =>  1,
            'created_at'  =>  date('Y-m-d H:i:s'),
        ]);
        $transactionInfo=\App\TransactionInfo::create([
            'txnid'  =>  $insTxn,
            'payment_addr'  =>  'Withdraw In MetaWallet',
            'payee_addr'  =>  '',
            'transaction_hash'  =>  'Withdraw In MetaWallet',
            'created_at'  =>  date('Y-m-d H:i:s'),
        ]);
        return $insTxn;
    }







}
