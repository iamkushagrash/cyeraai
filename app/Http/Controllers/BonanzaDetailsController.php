<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\UserDetails;
use App\User;
use DB;
use Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class BonanzaDetailsController extends Controller
{
    //Admin Silver
    public function userSearchSilverDowlinePage(){
        return view('control.silverdownlinesearch')->with('data',array());
    }
    public function userSearchSilverDowlineList(Request $request){
        set_time_limit(0);
        $datareg=$this->findUserName($request->userrid);

        $ar=array();
        $totalSilver = 0;
        $usrid=DB::table('user_details')->where($datareg['type'],$request->userrid)->join('users','users.id','=','user_details.userid')
        ->get()->pluck('userid')->first();

        if(is_null($usrid)){
            return redirect()->back()->with('warning','User Not Found');
        }
        
        $udata=DB::table('users')->where('id',$usrid)->get()->first();
        if(is_null($udata)){
            return view('control.silverdownlinesearch')->with('Warning','This User Does Not Exists.');
        }
        else{
                $uid=array($usrid);
                for($i=1;$i<10000;$i++){
                    
                    //$rData=DB::table('users')
                    //->whereIn('ud.sponsorid',$uid)
                    $rData=\App\User::whereIn('ud.sponsorid',$uid)->where('staketype',4)
                    ->join('user_details as ud','users.id','=','ud.userid')
                    ->join('users as gu','ud.sponsorid','=','gu.id')
                    ->join('stacking_deposites','ud.id','=','stacking_deposites.userid')
                    ->select('users.id as id','users.usersname as name','users.email as email','users.uuid as userid','ud.current_self_investment as current','ud.leveluser as leveluser','stacking_deposites.usdt as silveramount')
                    ->selectRaw('"Level-'. $i.'" as level,DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
                    ->selectRaw('case when ud.userstatus=0 then "Inactive" when ud.userstatus=1 then "Active" end as status')
                    ->selectRaw('case when ud.userstatus=0 then "status-cancelled" when ud.userstatus=1 then "status-complete" end as statusclass')
                    ->get();
                    foreach ($rData as $key ) {
                        $totalSilver += $key->silveramount;   // ⭐ total SUM
                       array_push($ar, $key);
                    }
                    $id=DB::table('user_details')->whereIn('sponsorid',$uid)->selectRaw('userid')->get()->pluck('userid');
                    if(count($id)==0||$id=="")
                        break;
                    else{  
                        $uid=$id;
                    }

                }
                

                /*dd($totalSilver);*/
                return view('control.silverdownlinesearch')->with('data',$ar)->with('totalAmount', $totalSilver);
            } 
    }

    public function getSilverWithdrawalAndReady(){
        set_time_limit(0);
        $silveruser=\App\StackingDeposite::where('stacking_deposites.staketype',4)
        /*->whereBetween('stacking_deposites.created_at',[$fromDate,$toDate])*/
        ->join('user_details','stacking_deposites.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        /*->join('stacking_details','stacking_deposites.planid','=','stacking_details.id')*/
        ->join('wallet_transfers','stacking_deposites.txnid','=','wallet_transfers.id')
        ->join('user_details as ud','wallet_transfers.fromUser','=','ud.id')
        ->join('users as u','ud.userid','=','u.id')
        ->select('user_details.id as id','stacking_deposites.userid as userid', 'users.email', 'users.uuid', 'users.usersname', 'u.usersname as fromname', 'u.uuid as fromid', 'wallet_transfers.fromWallet',DB::raw('SUM(stacking_deposites.usdt) as usdt'))
        ->selectRaw('MAX(DATE_FORMAT(stacking_deposites.created_at,"%d-%m-%Y")) as created_at')
        ->selectRaw('case when stacking_deposites.status=0 then "Closed" when stacking_deposites.status=1 then "Active" when stacking_deposites.status=3 then "Active" when stacking_deposites.status=4 then "Active" end as status')
        ->selectRaw('case when stacking_deposites.status=0 then "status-cancelled" when stacking_deposites.status=1 then "status-complete" when stacking_deposites.status=3 then "status-complete" when stacking_deposites.status=4 then "status-complete" end as statusclass')
        ->selectRaw('case when stacking_deposites.staketype=1 then "Wallet" when stacking_deposites.staketype=2 then "Loan" when stacking_deposites.staketype=3 then "Gold" when stacking_deposites.staketype=4 then "Silver" end as staketype')
        ->selectRaw('MAX(DATE_FORMAT(stacking_deposites.created_at,"%d-%m-%Y")) as last_deposit_date')
        ->groupBy('stacking_deposites.userid')
        ->orderByRaw('stacking_deposites.id DESC')
        ->get();
        /*dd($silveruser);*/
        return view('control.silverreadytorelease')->with('silver',$silveruser);
    }

    public function searchUserforSilverTeamWithdrawal(){
        return view('control.silverteamwithdrawal')->with('data',array());
    }
    public function searchUserbySilverTeamWithdrawal(Request $request)
    {
        set_time_limit(0);

        $fromDate = $request->fromdate ? $request->fromdate : date('Y-m-d');
        $toDate = $request->todate ? $request->todate.' 23:59:59' : date('Y-m-d').' 23:59:59';

        // STEP 1: Identify user (users.id for BFS)
        $datareg = $this->findUserName($request->userrid);

        $user_account_id = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.' . $datareg['type'], $request->userrid)
            ->value('users.id');

        if(!$user_account_id){
            return back()->with('warning','User Not Found');
        }

        // STEP 2: BFS on USERS.ID
        $team_user_ids = [];
        $queue = [$user_account_id];

        while(!empty($queue)){
            // Find children
            $children = DB::table('user_details')
                ->whereIn('sponsorid', $queue)     // sponsorid = users.id
                ->pluck('userid')                  // userid = users.id
                ->toArray();

            if(empty($children)) break;

            $team_user_ids = array_merge($team_user_ids, $children);
            $queue = $children;
        }

        if(empty($team_user_ids)){
            return view('control.silverteamwithdrawal')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 2.5: Filter only those with stacking_deposites staketype = 4
        $team_internal_ids = DB::table('user_details')
            ->whereIn('user_details.userid', $team_user_ids)          // users.id list
            ->join('stacking_deposites', function($join){
                $join->on('stacking_deposites.userid', '=', 'user_details.id')
                     ->where('stacking_deposites.staketype', 4);
            })
            ->pluck('user_details.id')                   // internal IDs
            ->toArray();

        if(empty($team_internal_ids)){
            return view('control.silverteamwithdrawal')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 3: Fetch transactions for filtered users
        $data = DB::table('transaction_details')
            ->join('user_details', 'user_details.id', '=', 'transaction_details.userid')
            ->join('users', 'users.id', '=', 'user_details.userid')
            ->join('users as gu', 'gu.id', '=', 'user_details.sponsorid')
            ->whereIn('transaction_details.userid', $team_internal_ids)
            ->where('transaction_details.txntype', 1)
            ->where('transaction_details.paymentstatus', 2)
            ->whereBetween('transaction_details.created_at', [$fromDate, $toDate])
            ->select(
                'users.id',
                'users.usersname as usersname',
                'users.email',
                'users.uuid as uuid',
                'transaction_details.amountsftc',
                'transaction_details.amountusdt',
                'transaction_details.net_amount',
                'transaction_details.deduction',
                'transaction_details.currency',
                'transaction_details.created_at as txndate',
                'transaction_details.updated_at as updated'
            )
            ->selectRaw('"Success" as status')
            ->selectRaw('"status-complete" as statusclass')
            ->get();

        $total = $data->sum('amountusdt');

        return view('control.silverteamwithdrawal')
            ->with('data', $data)
            ->with('totalAmount', $total);
    }







    //Normal search

    public function searchUserforBusiness(){
        return view('control.userbusinesssearch')->with('data',array());
    }
    public function searchUserbyBusiness(Request $request)
    {
        set_time_limit(0);

        $fromDate = $request->fromdate ?? date('Y-m-d');
        $toDate = ($request->todate ?? date('Y-m-d')) . ' 23:59:59';

        // STEP 1: Identify user (users.id for BFS)
        $datareg = $this->findUserName($request->userrid);

        $user_account_id = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.' . $datareg['type'], $request->userrid)
            ->value('users.id');

        if(!$user_account_id){
            return back()->with('warning','User Not Found');
        }

        // STEP 2: BFS on USERS.ID
        $team_user_ids = [];
        $queue = [$user_account_id];

        while(!empty($queue)){
            // Find children
            $children = DB::table('user_details')
                ->whereIn('sponsorid', $queue)     // sponsorid = users.id
                ->pluck('userid')                  // userid = users.id
                ->toArray();

            if(empty($children)) break;

            $team_user_ids = array_merge($team_user_ids, $children);
            $queue = $children;
        }

        if(empty($team_user_ids)){
            return view('control.userbusinesssearch')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 2.5: Filter only those with stacking_deposites staketype = 1
        $team_internal_ids = DB::table('user_details')
            ->whereIn('user_details.userid', $team_user_ids)          // users.id list
            ->join('stacking_deposites', function($join){
                $join->on('stacking_deposites.userid', '=', 'user_details.id')
                     ->where('stacking_deposites.staketype', 1);
            })
            ->pluck('user_details.id')                   // internal IDs
            ->toArray();

        if(empty($team_internal_ids)){
            return view('control.userbusinesssearch')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 3: Fetch transactions for filtered users
        $data = DB::table('stacking_deposites')
            ->join('user_details', 'user_details.id', '=', 'stacking_deposites.userid')
            ->join('users', 'users.id', '=', 'user_details.userid')
            ->whereIn('stacking_deposites.userid', $team_internal_ids)
            ->where('stacking_deposites.staketype', 1)
            ->whereBetween('stacking_deposites.created_at', [$fromDate, $toDate])
            ->select(
                'users.id',
                'users.usersname as name',
                'users.email',
                'users.uuid as userid','user_details.current_self_investment as current','stacking_deposites.usdt as amountusdt',
                'stacking_deposites.created_at as txndate'
            )
            ->selectRaw('"Success" as status')
            ->selectRaw('"status-complete" as statusclass')
            ->get();

        $total = $data->sum('amountusdt');

        return view('control.userbusinesssearch')
            ->with('data', $data)
            ->with('totalAmount', $total);
    }

    /*public function searchUserforBusiness(){
        return view('control.userbusinesssearch')->with('data',array());
    }
    public function searchUserbyBusiness(Request $request){
        set_time_limit(0);

        if(!isset($request->fromdate) || !isset($request->todate)){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }

        $datareg=$this->findUserName($request->userrid);

        $ar=array();
        $usrid=DB::table('user_details')->where($datareg['type'],$request->userrid)->join('users','users.id','=','user_details.userid')
        ->get()->pluck('userid')->first();

        if(is_null($usrid)){
            return redirect()->back()->with('warning','User Not Found');
        }
        
        $udata=DB::table('users')->where('id',$usrid)->get()->first();
        if(is_null($udata)){
            return view('control.userbusinesssearch')->with('Warning','This User Does Not Exists.');
        }
        else{
                $uid=array($usrid);
                for($i=1;$i<10000;$i++){
                    
                    //$rData=DB::table('users')
                    //->whereIn('ud.sponsorid',$uid)
                    $rData=\App\User::whereIn('ud.sponsorid',$uid)->where('staketype',1)
                    ->whereBetween('stacking_deposites.created_at',[$fromDate,$toDate])
                    ->join('user_details as ud','users.id','=','ud.userid')
                    ->join('users as gu','ud.sponsorid','=','gu.id')
                    ->join('stacking_deposites','ud.id','=','stacking_deposites.userid')
                    ->select('users.id as id','users.usersname as name','users.email as email','users.uuid as userid','ud.current_self_investment as current','stacking_deposites.usdt as amountusdt')
                    ->selectRaw('"Level-'. $i.'" as level,DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
                    ->selectRaw('case when ud.userstatus=0 then "Inactive" when ud.userstatus=1 then "Active" end as status')
                    ->selectRaw('case when ud.userstatus=0 then "status-cancelled" when ud.userstatus=1 then "status-complete" end as statusclass')
                    ->get();
                    foreach ($rData as $key ) {
                       array_push($ar, $key);
                    }
                    $id=DB::table('user_details')->whereIn('sponsorid',$uid)->selectRaw('userid')->get()->pluck('userid');
                    if(count($id)==0||$id=="")
                        break;
                    else{  
                        $uid=$id;
                    }

                }
                $totalAmount = collect($ar)->sum('amountusdt');
                
                return view('control.userbusinesssearch')->with('data',$ar)->with('totalAmount', $totalAmount);
            } 
            
    }*/


    public function searchUserforTeamWithdrawal(){
        return view('control.userteamwithdrawalsearch')->with('data',array());
    }
    public function searchUserbyTeamWithdrawal(Request $request)
    {
        set_time_limit(0);

        $fromDate = $request->fromdate ? $request->fromdate : date('Y-m-d');
        $toDate = $request->todate ? $request->todate.' 23:59:59' : date('Y-m-d').' 23:59:59';

        // Step-1: find user id
        $datareg = $this->findUserName($request->userrid);

        $usrid = DB::table('user_details')
            ->where($datareg['type'], $request->userrid)
            ->join('users','users.id','=','user_details.userid')
            ->value('userid');

        if(!$usrid){
            return back()->with('warning','User Not Found');
        }

        // Step-2: BFS (users.id based)
        $team_user_ids = [];
        $queue = [$usrid]; // users.id

        while(!empty($queue)){
            $children = DB::table('user_details')
                ->whereIn('sponsorid', $queue)  // sponsorid = users.id
                ->pluck('userid')               // userid = users.id
                ->toArray();

            if(empty($children)) break;

            $team_user_ids = array_merge($team_user_ids, $children);
            $queue = $children;
        }

        // Step-2.5: Convert users.id → user_details.id
        $team = DB::table('user_details')
            ->whereIn('userid', $team_user_ids)
            ->pluck('id') // internal ID for txns
            ->toArray();


        if(empty($team)){
            return view('control.userteamwithdrawalsearch')->with('data', [])->with('totalAmount', 0);
        }

        // Step-3: Fetch team withdrawal transactions
        $data = DB::table('transaction_details')
            ->join('user_details', 'user_details.id', '=', 'transaction_details.userid') // CORRECT
            ->join('users', 'users.id', '=', 'user_details.userid')                       // CORRECT
            ->join('users as gu', 'gu.id', '=', 'user_details.sponsorid')                // CORRECT
            ->whereIn('transaction_details.userid', $team)                                // team = user_details.id
            ->where('transaction_details.txntype', 1)
            ->where('transaction_details.paymentstatus', 2)
            ->whereBetween('transaction_details.created_at', [$fromDate, $toDate])
            ->select(
                'users.id',
                'users.usersname as usersname',
                'users.email',
                'users.uuid as uuid',
                'transaction_details.amountsftc',
                'transaction_details.amountusdt',
                'transaction_details.net_amount',
                'transaction_details.deduction',
                'transaction_details.currency',
                'transaction_details.created_at as txndate',
                'transaction_details.updated_at as updated'
            )
            ->selectRaw('"Success" as status')
            ->selectRaw('"status-complete" as statusclass')
            ->get();

        $total = $data->sum('amountusdt');


        return view('control.userteamwithdrawalsearch')
            ->with('data', $data)
            ->with('totalAmount', $total);
    }


    public function searchUserforReadyWithdrawal(){
        return view('control.searchreadywithdrawal')->with('data',array());
    }
    public function searchUserbyReadyWithdrawal(Request $request){
        set_time_limit(0);
        
        $datareg=$this->findUserName($request->userrid);

        $ar=array();
        $usrid=DB::table('user_details')->where($datareg['type'],$request->userrid)->join('users','users.id','=','user_details.userid')
        ->get()->pluck('userid')->first();

        if(is_null($usrid)){
            return redirect()->back()->with('warning','User Not Found');
        }
        
        $udata=DB::table('users')->where('id',$usrid)->get()->first();
        if(is_null($udata)){
            return view('control.searchreadywithdrawal')->with('Warning','This User Does Not Exists.');
        }
        else{
                $uid=array($usrid);
                for($i=1;$i<10000;$i++){
                    
                        //$rData=DB::table('users')
                        //->whereIn('ud.sponsorid',$uid)
                        $rData=\App\User::whereIn('ud.sponsorid',$uid)
                        ->join('user_details as ud','users.id','=','ud.userid')
                        ->join('users as gu','ud.sponsorid','=','gu.id')
                        ->select('users.id as id','users.usersname as name','users.email as email','users.uuid as userid','ud.current_self_investment as current','ud.leveluser as leveluser'/*,'gu.usersname as guidername','gu.user_gf as guidergf'*/)
                        ->selectRaw('"Level-'. $i.'" as level,DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
                        ->selectRaw('case when ud.userstatus=0 then "Inactive" when ud.userstatus=1 then "Active" end as status')
                        ->selectRaw('case when ud.userstatus=0 then "status-cancelled" when ud.userstatus=1 then "status-complete" end as statusclass')
                        ->get();
                        foreach ($rData as $key ) {
                           array_push($ar, $key);
                        }
                        $id=DB::table('user_details')->whereIn('sponsorid',$uid)->selectRaw('userid')->get()->pluck('userid');
                        if(count($id)==0||$id=="")
                            break;
                        else{  
                            $uid=$id;
                        }

                }
                /*dd($ar);*/
                return view('control.searchreadywithdrawal')->with('data',$ar);
            } 
    }

    public function searchUserforTeamClubIncome(){
        return view('control.userteamclubincomesearch')->with('data',array());
    }
    public function searchUserbyTeamClubIncome(Request $request){
        set_time_limit(0);

        $fromDate = $request->fromdate ?? date('Y-m-d');
        $toDate = ($request->todate ?? date('Y-m-d')) . ' 23:59:59';

        // STEP 1: Identify user (users.id for BFS)
        $datareg = $this->findUserName($request->userrid);

        $user_account_id = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.' . $datareg['type'], $request->userrid)
            ->value('users.id');

        if(!$user_account_id){
            return back()->with('warning','User Not Found');
        }

        // STEP 2: BFS on USERS.ID
        $team_user_ids = [];
        $queue = [$user_account_id];

        while(!empty($queue)){
            // Find children
            $children = DB::table('user_details')
                ->whereIn('sponsorid', $queue)     // sponsorid = users.id
                ->pluck('userid')                  // userid = users.id
                ->toArray();

            if(empty($children)) break;

            $team_user_ids = array_merge($team_user_ids, $children);
            $queue = $children;
        }

        if(empty($team_user_ids)){
            return view('control.userteamclubincomesearch')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 2.5: Filter only those with stacking_deposites staketype = 1
        $team_internal_ids = DB::table('user_details')
            ->whereIn('user_details.userid', $team_user_ids)          // users.id list
            /*->join('stacking_deposites', function($join){
                $join->on('stacking_deposites.userid', '=', 'user_details.id')
                     ->where('stacking_deposites.staketype', 1);
            })*/
            ->pluck('user_details.id')                   // internal IDs
            ->toArray();

        if(empty($team_internal_ids)){
            return view('control.userteamclubincomesearch')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 3: Fetch transactions for filtered users
        $data = DB::table('club_incomes')
            ->join('user_details', 'user_details.id', '=', 'club_incomes.userid')
            ->join('users', 'users.id', '=', 'user_details.userid')
            ->whereIn('club_incomes.userid', $team_internal_ids)
            ->whereBetween('club_incomes.created_at', [$fromDate, $toDate])
            ->select(
                'users.id',
                'users.usersname as name',
                'users.email',
                'users.uuid as userid','user_details.current_self_investment as current','club_incomes.amt_usdt as amountusdt',
                'club_incomes.created_at as txndate'
            )
            ->get();

        $total = $data->sum('amountusdt');

        return view('control.userteamclubincomesearch')
            ->with('data', $data)
            ->with('totalAmount', $total);
            
    }


    public function searchUserforTeamRewardIncome(){
        return view('control.userteamrewardincomesearch')->with('data',array());
    }
    public function searchUserbyTeamRewardIncome(Request $request){
        set_time_limit(0);

        $fromDate = $request->fromdate ?? date('Y-m-d');
        $toDate = ($request->todate ?? date('Y-m-d')) . ' 23:59:59';

        // STEP 1: Identify user (users.id for BFS)
        $datareg = $this->findUserName($request->userrid);

        $user_account_id = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.' . $datareg['type'], $request->userrid)
            ->value('users.id');

        if(!$user_account_id){
            return back()->with('warning','User Not Found');
        }

        // STEP 2: BFS on USERS.ID
        $team_user_ids = [];
        $queue = [$user_account_id];

        while(!empty($queue)){
            // Find children
            $children = DB::table('user_details')
                ->whereIn('sponsorid', $queue)     // sponsorid = users.id
                ->pluck('userid')                  // userid = users.id
                ->toArray();

            if(empty($children)) break;

            $team_user_ids = array_merge($team_user_ids, $children);
            $queue = $children;
        }

        if(empty($team_user_ids)){
            return view('control.userteamrewardincomesearch')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 2.5: Filter only those with stacking_deposites staketype = 1
        $team_internal_ids = DB::table('user_details')
            ->whereIn('user_details.userid', $team_user_ids)          // users.id list
            /*->join('stacking_deposites', function($join){
                $join->on('stacking_deposites.userid', '=', 'user_details.id')
                     ->where('stacking_deposites.staketype', 1);
            })*/
            ->pluck('user_details.id')                   // internal IDs
            ->toArray();

        if(empty($team_internal_ids)){
            return view('control.userteamrewardincomesearch')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 3: Fetch transactions for filtered users
        $data = DB::table('achievement_incomes')
            ->join('user_details', 'user_details.id', '=', 'achievement_incomes.userid')
            ->join('users', 'users.id', '=', 'user_details.userid')
            ->whereIn('achievement_incomes.userid', $team_internal_ids)
            ->whereBetween('achievement_incomes.created_at', [$fromDate, $toDate])
            ->select(
                'users.id',
                'users.usersname as name',
                'users.email',
                'users.uuid as userid','user_details.current_self_investment as current','achievement_incomes.amount as amountusdt',
                'achievement_incomes.created_at as txndate'
            )
            ->get();

        $total = $data->sum('amountusdt');

        return view('control.userteamrewardincomesearch')
            ->with('data', $data)
            ->with('totalAmount', $total);
            
    }



    public function searchUserforTotalReport(){
        return view('control.usertotalreportdownline')->with('data',array());
    }
    public function searchUserbyTotalReport(Request $request)
    {
        set_time_limit(0);

        /*$fromDate = $request->fromdate ?? date('Y-m-d');
        $toDate = ($request->todate ?? date('Y-m-d')) . ' 23:59:59';*/

        // STEP 1: Identify user (users.id for BFS)
        $datareg = $this->findUserName($request->userrid);

        $user_account_id = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.' . $datareg['type'], $request->userrid)
            ->value('users.id');

        if(!$user_account_id){
            return back()->with('warning','User Not Found');
        }

        // STEP 2: BFS on USERS.ID
        $team_user_ids = [];
        $visited = [];
        $queue = [$user_account_id];

        while (!empty($queue)) {

            $children = DB::table('user_details')
                ->whereIn('sponsorid', $queue)
                ->pluck('userid')
                ->toArray();

            $queue = [];

            foreach ($children as $child) {
                if (!in_array($child, $visited)) {
                    $visited[] = $child;
                    $team_user_ids[] = $child;
                    $queue[] = $child;
                }
            }
        }


        // STEP 3: Fetch transactions for filtered users
        /*$data = DB::table('users')
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->join('stacking_deposites', 'stacking_deposites.userid', '=', 'user_details.id')
        ->whereIn('users.id', $team_user_ids)
        ->select(
            'users.id',
            'users.usersname as name',
            'users.email',
            'users.doj',
            'users.uuid as userid',
            'user_details.current_self_investment as current',

            DB::raw('SUM(CASE WHEN stacking_deposites.staketype = 0 THEN stacking_deposites.usdt ELSE 0 END) as product'),
            DB::raw('SUM(CASE WHEN stacking_deposites.staketype = 1 THEN stacking_deposites.usdt ELSE 0 END) as wallet'),
            DB::raw('SUM(CASE WHEN stacking_deposites.staketype = 2 THEN stacking_deposites.usdt ELSE 0 END) as loan'),
            DB::raw('SUM(CASE WHEN stacking_deposites.staketype = 3 THEN stacking_deposites.usdt ELSE 0 END) as gold'),
            DB::raw('SUM(CASE WHEN stacking_deposites.staketype = 4 THEN stacking_deposites.usdt ELSE 0 END) as silver')
        )
        ->groupBy(
            'users.id',
            'users.usersname',
            'users.email',
            'users.doj',
            'users.uuid',
            'user_details.current_self_investment'
        )
        ->get();*/

        $data = DB::table('users')
            ->join('user_details', 'users.id', '=', 'user_details.userid')

            // ===== Staking Deposits Summary (Only usdt > 0) =====
            ->leftJoin(DB::raw('(
                SELECT userid,
                SUM(CASE WHEN staketype = 0 THEN usdt ELSE 0 END) as product,
                SUM(CASE WHEN staketype = 1 THEN usdt ELSE 0 END) as wallet,
                SUM(CASE WHEN staketype = 2 THEN usdt ELSE 0 END) as loan,
                SUM(CASE WHEN staketype = 3 THEN usdt ELSE 0 END) as gold,
                SUM(CASE WHEN staketype = 4 THEN usdt ELSE 0 END) as silver
                FROM stacking_deposites
                WHERE usdt > 0
                GROUP BY userid
            ) sd'), 'sd.userid', '=', 'user_details.id')

            // ===== Incomes =====
            ->leftJoin(DB::raw('
                (SELECT userid, SUM(amt_usdt) as staking_income 
                 FROM cps_incomes 
                 GROUP BY userid) si
            '), 'si.userid','=','user_details.id')

            ->leftJoin(DB::raw('
                (SELECT userid, SUM(amt_usdt) as direct_income 
                 FROM bonus_rewards 
                 GROUP BY userid) di
            '), 'di.userid','=','user_details.id')

            ->leftJoin(DB::raw('
                (SELECT userid, SUM(amt_usdt) as referral_income 
                 FROM level_incomes 
                 GROUP BY userid) ri
            '), 'ri.userid','=','user_details.id')

            ->leftJoin(DB::raw('
                (SELECT userid, SUM(amt_usdt) as club_income 
                 FROM club_incomes 
                 GROUP BY userid) ci
            '), 'ci.userid','=','user_details.id')

            ->leftJoin(DB::raw('
                (SELECT userid, SUM(amount) as lifetime_income 
                 FROM achievement_incomes 
                 GROUP BY userid) li
            '), 'li.userid','=','user_details.id')

            // ===== Withdrawals (txntype=1 AND paymentstatus=2) =====
            ->leftJoin(DB::raw('
                (SELECT userid, SUM(amountusdt) as total_withdrawal
                 FROM transaction_details
                 WHERE txntype = 1 AND paymentstatus = 2
                 GROUP BY userid) wd
            '), 'wd.userid','=','user_details.id')

            ->whereIn('users.id', $team_user_ids)

            // 🔥 Only users having stacking deposit
            ->whereNotNull('sd.userid')

            ->select(
                'users.id',
                'users.usersname as name',
                'users.email',
                'users.doj',
                'users.uuid as userid',
                'user_details.current_self_investment as current',

                DB::raw('COALESCE(sd.product,0) as product'),
                DB::raw('COALESCE(sd.wallet,0) as wallet'),
                DB::raw('COALESCE(sd.loan,0) as loan'),
                DB::raw('COALESCE(sd.gold,0) as gold'),
                DB::raw('COALESCE(sd.silver,0) as silver'),

                DB::raw('COALESCE(si.staking_income,0) as staking_income'),
                DB::raw('COALESCE(di.direct_income,0) as direct_income'),
                DB::raw('COALESCE(ri.referral_income,0) as staking_referral_income'),
                DB::raw('COALESCE(ci.club_income,0) as club_income'),
                DB::raw('COALESCE(li.lifetime_income,0) as lifetime_income'),

                DB::raw('(
                    COALESCE(si.staking_income,0) +
                    COALESCE(di.direct_income,0) +
                    COALESCE(ri.referral_income,0) +
                    COALESCE(ci.club_income,0) +
                    COALESCE(li.lifetime_income,0)
                ) as total_income'),

                DB::raw('COALESCE(wd.total_withdrawal,0) as total_withdrawal')
            )

            ->get();


        return view('control.usertotalreportdownline')
            ->with('data', $data);
    }





    /*public function searchUserbyTeamWithdrawal(Request $request){
        set_time_limit(0);

        if(!isset($request->fromdate) || !isset($request->todate)){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }

        $datareg=$this->findUserName($request->userrid);

        $ar=array();
        $usrid=DB::table('user_details')->where($datareg['type'],$request->userrid)->join('users','users.id','=','user_details.userid')
        ->get()->pluck('userid')->first();

        if(is_null($usrid)){
            return redirect()->back()->with('warning','User Not Found');
        }
        
        $udata=DB::table('users')->where('id',$usrid)->get()->first();
        if(is_null($udata)){
            return view('control.userteamwithdrawalsearch')->with('Warning','This User Does Not Exists.');
        }
        else{
                $uid=array($usrid);
                for($i=1;$i<10000;$i++){
                    
                    //$rData=DB::table('users')
                    //->whereIn('ud.sponsorid',$uid)
                    $rData=\App\User::whereIn('ud.sponsorid',$uid)->where([['transaction_details.txntype',1],['transaction_details.paymentstatus',2]])
                    ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
                    ->join('user_details as ud','users.id','=','ud.userid')
                    ->join('users as gu','ud.sponsorid','=','gu.id')
                    ->join('transaction_details','ud.id','=','transaction_details.userid')
                    ->select('users.id as id','users.usersname as name','users.email as email','users.uuid as userid','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'currency as currency', 'transaction_details.created_at as txndate', 'transaction_details.updated_at as updated')
                    ->selectRaw('case when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" end as status')
                    ->selectRaw('case when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" end as statusclass')
                    ->get();
                    dd($rData);
                    foreach ($rData as $key ) {
                       array_push($ar, $key);
                    }
                    $id=DB::table('user_details')->whereIn('sponsorid',$uid)->selectRaw('userid')->get()->pluck('userid');
                    if(count($id)==0||$id=="")
                        break;
                    else{  
                        $uid=$id;
                    }

                }
                $totalAmount = collect($ar)->sum('amountusdt');
                
                return view('control.userteamwithdrawalsearch')->with('data',$ar)->with('totalAmount', $totalAmount);
            } 
            
    }*/






}
