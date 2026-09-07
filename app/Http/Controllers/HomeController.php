<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /*public function index()
    {
        return view('frontend.home');
    }*/
    public function index()
    {
        return view('front2.index');
    }
    public function documentation()
    {
        return view('user.documentation');
    }

    public function bonanzaList()
    {
        return view('user.bonanza');
    }



    //Admin
    public function adminindex(){
        $totalmember=DB::table('users')->where('licence',1)
        ->get();
        $totalpaid=DB::table('users')->where([['users.licence','1'],['user_details.userstatus','1']])
        ->join('user_details','users.id','=','user_details.userid')
        ->get();
        $totalunpaid=DB::table('users')->where([['users.licence','1'],['user_details.userstatus','0']])
        ->join('user_details','users.id','=','user_details.userid')
        ->get();

        $basicrevenue=DB::table('stacking_deposites')->where([['fromWallet','wallet'],['toWallet','basic']])
        ->join('wallet_transfers','wallet_transfers.id','=','stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as basicrevenue'))
        ->get()->first();
        $loanrevenue=DB::table('stacking_deposites')->where([['fromWallet','loan'],['toWallet','basic']])
        ->join('wallet_transfers','wallet_transfers.id','=','stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as loanrevenue'))
        ->get()->first();
        $zeropin=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Topup'],['transaction_infos.payment_addr','Admin Topup']])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(transaction_details.amountsftc) as amountsftc,sum(amountusdt) as amountusdt'))
        ->get()->first();

        $totalroi=DB::table('cps_incomes')/*->where('txnDesc','CPS')*/
        ->select(DB::raw('sum(amount) as totalroi,sum(remaining) as remainingroi'))
        ->get()->first();
        $totallevel=DB::table('level_incomes')/*->where('txnDesc','Level')*/
        ->select(DB::raw('sum(amount) as totallevel,sum(remaining) as remaininglevel'))
        ->get()->first();
        $totalclub=DB::table('club_incomes')
        ->select(DB::raw('sum(amount) as totalclub,sum(remaining) as remainingclub'))
        ->get()->first();
        $totalbonus=DB::table('bonus_rewards')->where('status','!=',3)
        ->select(DB::raw('sum(amount) as totalbonus,sum(remaining) as remainingbonus'))
        ->get()->first();
        /*$totalsalary=DB::table('salary_incomes')
        ->select(DB::raw('sum(amount) as totalsalary,sum(remaining) as remainingsalary'))
        ->get()->first();*/

        $unpaidwithdraw=$totalroi->remainingroi+$totallevel->remaininglevel+$totalbonus->remainingbonus+$totalclub->remainingclub/*+$totalsalary->remainingsalary*/;

        $totalrevenue=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','!=','Admin Topup']])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

        $totalwithdraw=DB::table('transaction_details')->where([['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',2]])
        ->select(DB::raw('sum(amountusdt) as totalwithdraw'))
        ->get()->first();

        $pendingwithdraw=DB::table('transaction_details')->where([['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',1]])
        ->select(DB::raw('sum(amountusdt) as pendingwithdraw'))
        ->get()->first();

        $totalloan=DB::table('loan_details')
        ->select(DB::raw('sum(amount) as totalloan,sum(remaining) as remainingloan'))
        ->get()->first();
        

        $rdata['totalmember']=$totalmember;
        $rdata['totalpaid']=$totalpaid;
        $rdata['totalunpaid']=$totalunpaid;
        $rdata['basicrevenue']=$basicrevenue;
        $rdata['loanrevenue']=$loanrevenue;
        $rdata['unpaidwithdraw']=$unpaidwithdraw;
        $rdata['totalrevenue']=$totalrevenue;
        $rdata['totalwithdraw']=$totalwithdraw;
        $rdata['pendingwithdraw']=$pendingwithdraw;
        $rdata['totalloan']=$totalloan;
        $rdata['zeropin']=$zeropin;

        return view('control.dashboard')->with('data',$rdata);
    }



    //User
    public function userindex(){
        $userdetails=\App\UserDetails::where([['user_details.id',Session::get('user.id')]])
        ->leftJoin(DB::raw('(SELECT * FROM stacking_deposites WHERE id IN (SELECT MIN(id) FROM stacking_deposites GROUP BY userid)) as first_stack'), 'user_details.id', '=', 'first_stack.userid')
        ->select('total_direct as totaldirect','active_direct as activedirect','total_downline as totaldownline','active_downline as activedownline','total_direct_investment as directbusiness','total_level_investment as levelbusiness','total_investment as totalbusiness','current_self_investment as currentself','capping as capping','booster as booster')
        ->selectRaw('case when first_stack.staketype=1 then "3cd2a5 " when first_stack.staketype=2 then "FF0000 " when first_stack.staketype=3 then "FFD700 " when first_stack.staketype=4 then "ffffff " else "FF0000 " end as statusclass')
        ->get()->first();

        $userDetail=\App\UserDetails::where('id',\Session::get('user.id'))->first();

        $totaldirect=DB::table('user_details')->where([['user_details.id',Session::get('user.id')],['u.permission','1']])
        ->join('user_details as ud','ud.sponsorid','=','user_details.userid')
        ->join('users as u','u.id','=','ud.userid')
        ->select(DB::raw('count(ud.id) as total'))
        ->get()->first();
        $activedirect=DB::table('user_details')->where([['user_details.id',Session::get('user.id')],['ud.userstatus','1'],['u.permission','1']])
        ->join('user_details as ud','ud.sponsorid','=','user_details.userid')
        ->join('users as u','u.id','=','ud.userid')
        ->select(DB::raw('count(ud.id) as active'))
        ->get()->first();

        

        $totalwithdraw=DB::table('transaction_details')->where([['transaction_details.userid',Session::get('user.id')],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',2]])
        ->select(DB::raw('sum(amountsftc) as amount,sum(amountusdt) as amountusdt'))
        ->get()->first();

        /*$totalroireceived=DB::table('cps_incomes')->where([['userid',Session::get('user.id')]])
        ->select(DB::raw('sum(amount) as totalroi'))
        ->get()->first();
        $unpaidroireceived=DB::table('cps_incomes')->where([['userid',Session::get('user.id')]])
        ->select(DB::raw('sum(remaining) as remainingroi'))
        ->get()->first();
        $totallevelreceived=DB::table('level_incomes')->where([['userid',Session::get('user.id')],['description','L']])
        ->select(DB::raw('sum(amount) as totallevel'))
        ->get()->first();
        $unpaidlevelreceived=DB::table('level_incomes')->where([['userid',Session::get('user.id')],['description','L']])
        ->select(DB::raw('sum(remaining) as remaininglevel'))
        ->get()->first();
        
        $totaldirectreceived=DB::table('bonus_rewards')->where([['userid',Session::get('user.id')]])
        ->select(DB::raw('sum(amount) as totaldirect'))
        ->get()->first();
        $unpaiddirectreceived=DB::table('bonus_rewards')->where([['userid',Session::get('user.id')]])
        ->select(DB::raw('sum(remaining) as remainingdirect'))
        ->get()->first();

        $totalincome=$totalroireceived->totalroi+$totallevelreceived->totallevel+$totaldirectreceived->totaldirect;
        $unpaidincome=$unpaidroireceived->remainingroi+$unpaidlevelreceived->remaininglevel+$unpaiddirectreceived->remainingdirect;*/

        $styprice=DB::table('profile_stores')->where('id',1)->get()->first();
        $availblewallet=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();


        $getIncomingFund=\App\WalletTransfer::where([['userid',\Session::get('user.id')],/*['txnid','!=',0],*/['toWallet','wallet']])->get();
        $getOutgoingFund=\App\WalletTransfer::where([['fromUser',\Session::get('user.id')],['txnid',0],['fromWallet','wallet']])->get();
        //dd($getIncomingFund->sum('amount'),$getOutgoingFund->sum('amount'));


        $productAmount=\App\StackingDeposite::where('staketype',0)->where('userid',$userDetail->id)->sum('usdt');

        $userlevel = \App\UserDetails::where('id', Session::get('user.id'))->first()->levelStatus();
        $adminlevel = \App\UserDetails::where('id', Session::get('user.id'))->first()->leveluser;
        if ($userlevel >= $adminlevel) {
            $level = $userlevel;
        } 
        else {
            $level = $adminlevel;
        }



        // Booster Logic
        if ($userDetail->booster == 2) {
                 $boosterStatus = "Active";
                    $boosterExpiry = null;
                    $activationDate = null;
                    $packageAmount = 0;
                    $directsCount = 0;
                    $neededDirects = 5;
                 } else {
                    $boosterStatus = "Inactive";
                    $firstdeposit=\App\StackingDeposite::where([
                        ['stacking_deposites.userid',$userDetail->id],
                        ['wallet_transfers.fromWallet','!=','loan']
                    ])
                    ->join('wallet_transfers','stacking_deposites.txnid','=','wallet_transfers.id')
                    ->select('stacking_deposites.created_at as activationdate', 'stacking_deposites.usdt as package_amount')
                    ->first();

                        if (!is_null($firstdeposit)) {
                            $activationDate = \Carbon\Carbon::parse($firstdeposit->activationdate);
                            $boosterExpiry = $activationDate->copy()->addDays(7);
                            $packageAmount = $firstdeposit->package_amount; // current user package amount
                        } else {
                            $activationDate = null;
                            $boosterExpiry = null;
                             $packageAmount = 0;
                        }
                        $directsCount = 0;

                        if (!is_null($activationDate)) {
                            $directsCount = \App\UserDetails::where('sponsorid', $userDetail->userid) // direct users
                                ->join('stacking_deposites as sd', 'sd.userid', '=', 'user_details.id')
                                ->join('wallet_transfers as wt', 'sd.txnid', '=', 'wt.id')
                                ->where('wt.fromWallet','!=','loan')
                                ->where('sd.usdt', '>=', $packageAmount) // same or above package
                                ->whereBetween('sd.created_at', [$activationDate, $boosterExpiry]) // 7 days condition
                                ->count();
                        }

                        $neededDirects = max(0, 5 - $directsCount);

                    }

        $usrRaw['userdetails']=$userdetails;
        $usrRaw['userDetail']=$userDetail;
        $usrRaw['totaldirect']=$totaldirect;
        $usrRaw['activedirect']=$activedirect;
        /*$usrRaw['totalincome']=$totalincome;
        $usrRaw['unpaidincome']=$unpaidincome;*/
        $usrRaw['totalwithdraw']=$totalwithdraw;
        /*$usrRaw['totaldirectreceived']=$totaldirectreceived;*/
        $usrRaw['styprice']=$styprice;
        $usrRaw['availblewallet']=$availblewallet;
        $usrRaw['incomingfund']=$getIncomingFund->sum('amount');
        $usrRaw['outgoingfund']=$getOutgoingFund->sum('amount');
        $usrRaw['level'] = $level;  
        $usrRaw['boosterExpiry']=$boosterExpiry ?? null;
        $usrRaw['activationdate']= $activationDate ?? null;
        $usrRaw['boosterDirectsAchieved'] = $directsCount;
        $usrRaw['boosterDirectsNeeded'] = $neededDirects;
        $usrRaw['productAmount'] = $productAmount;

        
        return view('user.dashboard')->with('data',$usrRaw);
    }

    
    public function userarbitrageindex(){
        $view =view('user.arbitrage');

        $userdetails=\App\UserDetails::where([['user_details.id',\Session::get('user.id')]])
        ->select('id as id','userstatus as userstatus','total_direct as totaldirect','active_direct as activedirect','total_downline as totaldownline','active_downline as activedownline','current_direct_investment as directbusiness','current_investment as totalbusiness','current_self_investment as currentself')
        ->get()->first();

        $plans=\App\StackingDeposite::where([['status',1],['userid',\Session::get('user.id')]])->get();
        //dd();
        
        $usrRaw['userdetails']=$userdetails;
        $usrRaw['plans']=$plans;
        
        return view('user.dashboardarbitrage')->with('data',$usrRaw)->with('arb',$view);
    }

public function adminindexabhi(Request $request){
    set_time_limit(0);
    // Get filter parameters from request
    $filter = $request->input('filter', 'today');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    
    // Set date ranges based on filter
    if ($filter === 'today') {
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
    } elseif ($filter === 'weekly') {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
    } elseif ($filter === 'monthly') {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
    } elseif ($filter === 'custom' && $start_date && $end_date) {
        $startDate = Carbon::parse($start_date)->startOfDay();
        $endDate = Carbon::parse($end_date)->endOfDay();
    } else {
        // Default to today if no valid filter
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $filter = 'today';
    }

    $price=\App\ProfileStore::where('id',1)->first();
    
    // Apply date filters to all queries
    $totalmember = DB::table('users')->where('licence', 1)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();
        
    $totalpaid = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '1']
        ])
        ->whereBetween('users.created_at', [$startDate, $endDate])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();
        
    $totalunpaid = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '0']
        ])
        ->whereBetween('users.created_at', [$startDate, $endDate])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();

    $basicrevenue = DB::table('stacking_deposites')->where([
            ['fromWallet', 'wallet'],
            ['toWallet', 'basic'],['staketype',1]
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as basicrevenue'))
        ->get()->first();
        
    $loanrevenue = DB::table('stacking_deposites')->where([
            ['fromWallet', 'loan'],
            ['toWallet', 'basic']
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as loanrevenue'))
        ->get()->first();
        
    $zeropin = DB::table('stacking_deposites')->where([
            ['staketype', 3],
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $silverpin = DB::table('stacking_deposites')->where([
            ['staketype', 4],
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $totalroi = DB::table('cps_incomes')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totalroi,sum(remaining_usdt) as remainingroi'))
        ->get()->first();
        
    $totallevel = DB::table('level_incomes')->where('description','l')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();
        
    $totaldevelopment = DB::table('level_incomes')->where('description','r')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();
        
    $totalclub = DB::table('club_incomes')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totalclub,sum(remaining_usdt) as remainingclub'))
        ->get()->first();
        
    $totalachievement = DB::table('achievement_incomes')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amount) as totallifetime,sum(remaining) as remaininglifetime'))
        ->get()->first();
        
    $totalbonus = DB::table('bonus_rewards')->where('status','!=',3)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totalbonus,sum(remaining_usdt) as remainingbonus'))
        ->get()->first();

    $unpaidwithdraw = ($totalroi->remainingroi ?? 0) + ($totallevel->remaininglevel ?? 0) + ($totaldevelopment->remaininglevel ?? 0) + ($totalbonus->remainingbonus ?? 0) + ($totalclub->remainingclub ?? 0);

    $totalrevenue = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', '!=', 'Admin Topup']
        ])
        ->whereBetween('transaction_details.created_at', [$startDate, $endDate])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $walletrevenue = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', 'Admin Deposit']
        ])
        ->whereBetween('transaction_details.created_at', [$startDate, $endDate])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $totalwithdraw = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 2]
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amountusdt) as totalwithdraw'))
        ->get()->first();

    $pendingwithdraw = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 1]
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amountusdt) as pendingwithdraw'))
        ->get()->first();

    $totalloan = DB::table('loan_details')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amount) as totalloan,sum(remaining) as remainingloan'))
        ->get()->first();
        
    // Handle cases where no records are found
    $basicrevenue = $basicrevenue ?? (object)['basicrevenue' => 0];
    $loanrevenue = $loanrevenue ?? (object)['loanrevenue' => 0];
    $zeropin = $zeropin ?? (object)['amountusdt' => 0];
    $silverpin = $silverpin ?? (object)['amountusdt' => 0];
    $totalroi = $totalroi ?? (object)['totalroi' => 0, 'remainingroi' => 0];
    $totallevel = $totallevel ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $totaldevelopment = $totaldevelopment ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $totalbonus = $totalbonus ?? (object)['totalbonus' => 0, 'remainingbonus' => 0];
    $totalclub = $totalclub ?? (object)['totalclub' => 0, 'remainingclub' => 0];
    $totalachievement = $totalachievement ?? (object)['totallifetime' => 0, 'remaininglifetime' => 0];
    $totalrevenue = $totalrevenue ?? (object)['totalrevenue' => 0];
    $walletrevenue = $walletrevenue ?? (object)['totalrevenue' => 0];
    $totalwithdraw = $totalwithdraw ?? (object)['totalwithdraw' => 0];
    $pendingwithdraw = $pendingwithdraw ?? (object)['pendingwithdraw' => 0];
    $totalloan = $totalloan ?? (object)['totalloan' => 0, 'remainingloan' => 0];

    // ALL-TIME QUERIES (without date filtering)
    $total_member_all_time = DB::table('users')->where('licence', 1)->get();
    
    $total_paid_all_time = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '1']
        ])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();
        
    $total_unpaid_all_time = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '0']
        ])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();

    $basic_revenue_all_time = DB::table('stacking_deposites')->where([
            ['fromWallet', 'wallet'],
            ['toWallet', 'basic'],['staketype',1]
        ])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as basicrevenue'))
        ->get()->first();
        
    $loan_revenue_all_time = DB::table('stacking_deposites')->where([
            ['fromWallet', 'loan'],
            ['toWallet', 'basic']
        ])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as loanrevenue'))
        ->get()->first();
        
    $zero_pin_all_time = DB::table('stacking_deposites')->where([
            ['staketype', 3],
        ])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $silver_pin_all_time = DB::table('stacking_deposites')->where([
            ['staketype', 4],
        ])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $total_roi_all_time = DB::table('cps_incomes')
        ->select(DB::raw('sum(amt_usdt) as totalroi,sum(remaining_usdt) as remainingroi'))
        ->get()->first();
        
    $total_level_all_time = DB::table('level_incomes')->where('description','l')
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();

    $total_development_all_time = DB::table('level_incomes')->where('description','r')
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();
        
    $total_bonus_all_time = DB::table('bonus_rewards')->where('status','!=',3)
        ->select(DB::raw('sum(amt_usdt) as totalbonus,sum(remaining_usdt) as remainingbonus'))
        ->get()->first();
        
    $total_club_all_time = DB::table('club_incomes')
        ->select(DB::raw('sum(amt_usdt) as totalclub,sum(remaining_usdt) as remainingclub'))
        ->get()->first();
        
    $total_achievement_all_time = DB::table('achievement_incomes')
        ->select(DB::raw('sum(amount) as totallifetime,sum(remaining) as remaininglifetime'))
        ->get()->first();

    $unpaid_withdraw_all_time = ($total_roi_all_time->remainingroi ?? 0) + ($total_level_all_time->remaininglevel ?? 0) + ($total_development_all_time->remaininglevel ?? 0) + ($total_bonus_all_time->remainingbonus ?? 0) + ($total_club_all_time->remainingclub ?? 0);

    $total_revenue_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', '!=', 'Admin Topup']
        ])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $wallet_revenue_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', 'Admin Deposit']
        ])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $total_withdraw_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 2]
        ])
        ->select(DB::raw('sum(amountusdt) as totalwithdraw'))
        ->get()->first();

    $pending_withdraw_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 1]
        ])
        ->select(DB::raw('sum(amountusdt) as pendingwithdraw'))
        ->get()->first();

    $total_loan_all_time = DB::table('loan_details')
        ->select(DB::raw('sum(amount) as totalloan,sum(remaining) as remainingloan'))
        ->get()->first();
        
    // Handle cases where no records are found for all-time queries
    $basic_revenue_all_time = $basic_revenue_all_time ?? (object)['basicrevenue' => 0];
    $loan_revenue_all_time = $loan_revenue_all_time ?? (object)['loanrevenue' => 0];
    $zero_pin_all_time = $zero_pin_all_time ?? (object)['amountusdt' => 0];
    $silver_pin_all_time = $silver_pin_all_time ?? (object)['amountusdt' => 0];
    $total_roi_all_time = $total_roi_all_time ?? (object)['totalroi' => 0, 'remainingroi' => 0];
    $total_level_all_time = $total_level_all_time ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $total_development_all_time = $total_development_all_time ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $total_bonus_all_time = $total_bonus_all_time ?? (object)['totalbonus' => 0, 'remainingbonus' => 0];
    $total_club_all_time = $total_club_all_time ?? (object)['totalclub' => 0, 'remainingclub' => 0];
    $total_achievement_all_time = $total_achievement_all_time ?? (object)['totallifetime' => 0, 'remaininglifetime' => 0];
    $total_revenue_all_time = $total_revenue_all_time ?? (object)['totalrevenue' => 0];
    $wallet_revenue_all_time = $wallet_revenue_all_time ?? (object)['totalrevenue' => 0];
    $total_withdraw_all_time = $total_withdraw_all_time ?? (object)['totalwithdraw' => 0];
    $pending_withdraw_all_time = $pending_withdraw_all_time ?? (object)['pendingwithdraw' => 0];
    $total_loan_all_time = $total_loan_all_time ?? (object)['totalloan' => 0, 'remainingloan' => 0];

    $rdata['totalmember'] = $totalmember;
    $rdata['totalpaid'] = $totalpaid;
    $rdata['totalunpaid'] = $totalunpaid;
    $rdata['basicrevenue'] = $basicrevenue;
    $rdata['loanrevenue'] = $loanrevenue;
    $rdata['unpaidwithdraw'] = $unpaidwithdraw;
    $rdata['totalroi'] = $totalroi;
    $rdata['totallevel'] = $totallevel;
    $rdata['totaldevelopment'] = $totaldevelopment;
    $rdata['totalbonus'] = $totalbonus;
    $rdata['totalclub'] = $totalclub;
    $rdata['totalachievement'] = $totalachievement;
    $rdata['totalrevenue'] = $totalrevenue;
    $rdata['walletrevenue'] = $walletrevenue;
    $rdata['totalwithdraw'] = $totalwithdraw;
    $rdata['pendingwithdraw'] = $pendingwithdraw;
    $rdata['totalloan'] = $totalloan;
    $rdata['zeropin'] = $zeropin;
    $rdata['silverpin'] = $silverpin;

    // Add all-time data
    $rdata['total_member_all_time'] = $total_member_all_time;
    $rdata['total_paid_all_time'] = $total_paid_all_time;
    $rdata['total_unpaid_all_time'] = $total_unpaid_all_time;
    $rdata['basic_revenue_all_time'] = $basic_revenue_all_time;
    $rdata['loan_revenue_all_time'] = $loan_revenue_all_time;
    $rdata['zero_pin_all_time'] = $zero_pin_all_time;
    $rdata['silver_pin_all_time'] = $silver_pin_all_time;
    $rdata['total_roi_all_time'] = $total_roi_all_time;
    $rdata['total_level_all_time'] = $total_level_all_time;
    $rdata['total_development_all_time'] = $total_development_all_time;
    $rdata['total_bonus_all_time'] = $total_bonus_all_time;
    $rdata['total_club_all_time'] = $total_club_all_time;
    $rdata['total_achievement_all_time'] = $total_achievement_all_time;
    $rdata['unpaid_withdraw_all_time'] = $unpaid_withdraw_all_time;
    $rdata['total_revenue_all_time'] = $total_revenue_all_time;
    $rdata['wallet_revenue_all_time'] = $wallet_revenue_all_time;
    $rdata['total_withdraw_all_time'] = $total_withdraw_all_time;
    $rdata['pending_withdraw_all_time'] = $pending_withdraw_all_time;
    $rdata['total_loan_all_time'] = $total_loan_all_time;

    // Add filter information to data
    $rdata['current_filter'] = $filter;
    $rdata['start_date'] = $start_date;
    $rdata['end_date'] = $end_date;
    $rdata['today'] = Carbon::today()->format('Y-m-d');
    $rdata['week_start'] = Carbon::now()->startOfWeek()->format('Y-m-d');
    $rdata['week_end'] = Carbon::now()->endOfWeek()->format('Y-m-d');
    $rdata['month_start'] = Carbon::now()->startOfMonth()->format('Y-m-d');
    $rdata['month_end'] = Carbon::now()->endOfMonth()->format('Y-m-d');

    $rdata['price'] = $price->price;

    return view('control.dashboard')->with('data', $rdata);
}

public function adminindexabhitoday(Request $request){
    set_time_limit(0);
    // Get filter parameters from request
    $filter = $request->input('filter', 'today');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    
    // Set date ranges based on filter
    if ($filter === 'today') {
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
    } elseif ($filter === 'weekly') {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();
    } elseif ($filter === 'monthly') {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
    } elseif ($filter === 'custom' && $start_date && $end_date) {
        $startDate = Carbon::parse($start_date)->startOfDay();
        $endDate = Carbon::parse($end_date)->endOfDay();
    } else {
        // Default to today if no valid filter
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $filter = 'today';
    }

    $price=\App\ProfileStore::where('id',1)->first();
    
    // Apply date filters to all queries
    $totalmember = DB::table('users')->where('licence', 1)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();
        
    $totalpaid = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '1']
        ])
        ->whereBetween('users.created_at', [$startDate, $endDate])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();
        
    $totalunpaid = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '0']
        ])
        ->whereBetween('users.created_at', [$startDate, $endDate])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();

    $basicrevenue = DB::table('stacking_deposites')->where([
            ['fromWallet', 'wallet'],
            ['toWallet', 'basic'],['staketype',1]
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as basicrevenue'))
        ->get()->first();
        
    $loanrevenue = DB::table('stacking_deposites')->where([
            ['fromWallet', 'loan'],
            ['toWallet', 'basic']
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as loanrevenue'))
        ->get()->first();
        
    $zeropin = DB::table('stacking_deposites')->where([
            ['staketype', 3],
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $silverpin = DB::table('stacking_deposites')->where([
            ['staketype', 4],
        ])
        ->whereBetween('stacking_deposites.created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $totalroi = DB::table('cps_incomes')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totalroi,sum(remaining_usdt) as remainingroi'))
        ->get()->first();
        
    $totallevel = DB::table('level_incomes')->where('description','l')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();
        
    $totaldevelopment = DB::table('level_incomes')->where('description','r')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();
        
    $totalclub = DB::table('club_incomes')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totalclub,sum(remaining_usdt) as remainingclub'))
        ->get()->first();
        
    $totalachievement = DB::table('achievement_incomes')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amount) as totallifetime,sum(remaining) as remaininglifetime'))
        ->get()->first();
        
    $totalbonus = DB::table('bonus_rewards')->where('status','!=',3)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amt_usdt) as totalbonus,sum(remaining_usdt) as remainingbonus'))
        ->get()->first();

    $unpaidwithdraw = ($totalroi->remainingroi ?? 0) + ($totallevel->remaininglevel ?? 0) + ($totaldevelopment->remaininglevel ?? 0) + ($totalbonus->remainingbonus ?? 0) + ($totalclub->remainingclub ?? 0);

    $totalrevenue = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', '!=', 'Admin Topup']
        ])
        ->whereBetween('transaction_details.created_at', [$startDate, $endDate])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $walletrevenue = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', 'Admin Deposit']
        ])
        ->whereBetween('transaction_details.created_at', [$startDate, $endDate])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $totalwithdraw = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 2]
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amountusdt) as totalwithdraw'))
        ->get()->first();

    $pendingwithdraw = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 1]
        ])
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amountusdt) as pendingwithdraw'))
        ->get()->first();

    $totalloan = DB::table('loan_details')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->select(DB::raw('sum(amount) as totalloan,sum(remaining) as remainingloan'))
        ->get()->first();
        
    // Handle cases where no records are found
    $basicrevenue = $basicrevenue ?? (object)['basicrevenue' => 0];
    $loanrevenue = $loanrevenue ?? (object)['loanrevenue' => 0];
    $zeropin = $zeropin ?? (object)['amountusdt' => 0];
    $silverpin = $silverpin ?? (object)['amountusdt' => 0];
    $totalroi = $totalroi ?? (object)['totalroi' => 0, 'remainingroi' => 0];
    $totallevel = $totallevel ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $totaldevelopment = $totaldevelopment ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $totalbonus = $totalbonus ?? (object)['totalbonus' => 0, 'remainingbonus' => 0];
    $totalclub = $totalclub ?? (object)['totalclub' => 0, 'remainingclub' => 0];
    $totalachievement = $totalachievement ?? (object)['totallifetime' => 0, 'remaininglifetime' => 0];
    $totalrevenue = $totalrevenue ?? (object)['totalrevenue' => 0];
    $walletrevenue = $walletrevenue ?? (object)['totalrevenue' => 0];
    $totalwithdraw = $totalwithdraw ?? (object)['totalwithdraw' => 0];
    $pendingwithdraw = $pendingwithdraw ?? (object)['pendingwithdraw' => 0];
    $totalloan = $totalloan ?? (object)['totalloan' => 0, 'remainingloan' => 0];


    $rdata['totalmember'] = $totalmember;
    $rdata['totalpaid'] = $totalpaid;
    $rdata['totalunpaid'] = $totalunpaid;
    $rdata['basicrevenue'] = $basicrevenue;
    $rdata['loanrevenue'] = $loanrevenue;
    $rdata['unpaidwithdraw'] = $unpaidwithdraw;
    $rdata['totalroi'] = $totalroi;
    $rdata['totallevel'] = $totallevel;
    $rdata['totaldevelopment'] = $totaldevelopment;
    $rdata['totalbonus'] = $totalbonus;
    $rdata['totalclub'] = $totalclub;
    $rdata['totalachievement'] = $totalachievement;
    $rdata['totalrevenue'] = $totalrevenue;
    $rdata['walletrevenue'] = $walletrevenue;
    $rdata['totalwithdraw'] = $totalwithdraw;
    $rdata['pendingwithdraw'] = $pendingwithdraw;
    $rdata['totalloan'] = $totalloan;
    $rdata['zeropin'] = $zeropin;
    $rdata['silverpin'] = $silverpin;


    // Add filter information to data
    $rdata['current_filter'] = $filter;
    $rdata['start_date'] = $start_date;
    $rdata['end_date'] = $end_date;
    $rdata['today'] = Carbon::today()->format('Y-m-d');
    $rdata['week_start'] = Carbon::now()->startOfWeek()->format('Y-m-d');
    $rdata['week_end'] = Carbon::now()->endOfWeek()->format('Y-m-d');
    $rdata['month_start'] = Carbon::now()->startOfMonth()->format('Y-m-d');
    $rdata['month_end'] = Carbon::now()->endOfMonth()->format('Y-m-d');

    $rdata['price'] = $price->price;

    return view('control.dashboardtoday')->with('data', $rdata);
}


public function adminindexabhialltime(Request $request){
    set_time_limit(0);


    $price=\App\ProfileStore::where('id',1)->first();
 
        
    // Handle cases where no records are found
    $basicrevenue = $basicrevenue ?? (object)['basicrevenue' => 0];
    $loanrevenue = $loanrevenue ?? (object)['loanrevenue' => 0];
    $zeropin = $zeropin ?? (object)['amountusdt' => 0];
    $silverpin = $silverpin ?? (object)['amountusdt' => 0];
    $totalroi = $totalroi ?? (object)['totalroi' => 0, 'remainingroi' => 0];
    $totallevel = $totallevel ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $totaldevelopment = $totaldevelopment ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $totalbonus = $totalbonus ?? (object)['totalbonus' => 0, 'remainingbonus' => 0];
    $totalclub = $totalclub ?? (object)['totalclub' => 0, 'remainingclub' => 0];
    $totalachievement = $totalachievement ?? (object)['totallifetime' => 0, 'remaininglifetime' => 0];
    $totalrevenue = $totalrevenue ?? (object)['totalrevenue' => 0];
    $walletrevenue = $walletrevenue ?? (object)['totalrevenue' => 0];
    $totalwithdraw = $totalwithdraw ?? (object)['totalwithdraw' => 0];
    $pendingwithdraw = $pendingwithdraw ?? (object)['pendingwithdraw' => 0];
    $totalloan = $totalloan ?? (object)['totalloan' => 0, 'remainingloan' => 0];

    // ALL-TIME QUERIES (without date filtering)
    $total_member_all_time = DB::table('users')->where('licence', 1)->get();
    
    $total_paid_all_time = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '1']
        ])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();
        
    $total_unpaid_all_time = DB::table('users')->where([
            ['users.licence', '1'],
            ['user_details.userstatus', '0']
        ])
        ->join('user_details', 'users.id', '=', 'user_details.userid')
        ->get();

    $basic_revenue_all_time = DB::table('stacking_deposites')->where([
            ['fromWallet', 'wallet'],
            ['toWallet', 'basic'],['staketype',1]
        ])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as basicrevenue'))
        ->get()->first();
        
    $loan_revenue_all_time = DB::table('stacking_deposites')->where([
            ['fromWallet', 'loan'],
            ['toWallet', 'basic']
        ])
        ->join('wallet_transfers', 'wallet_transfers.id', '=', 'stacking_deposites.txnid')
        ->select(DB::raw('sum(usdt) as loanrevenue'))
        ->get()->first();
        
    $zero_pin_all_time = DB::table('stacking_deposites')->where([
            ['staketype', 3],
        ])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $silver_pin_all_time = DB::table('stacking_deposites')->where([
            ['staketype', 4],
        ])
        ->select(DB::raw('sum(usdt) as amountusdt'))
        ->get()->first();

    $total_roi_all_time = DB::table('cps_incomes')
        ->select(DB::raw('sum(amt_usdt) as totalroi,sum(remaining_usdt) as remainingroi'))
        ->get()->first();
        
    $total_level_all_time = DB::table('level_incomes')->where('description','l')
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();

    $total_development_all_time = DB::table('level_incomes')->where('description','r')
        ->select(DB::raw('sum(amt_usdt) as totallevel,sum(remaining_usdt) as remaininglevel'))
        ->get()->first();
        
    $total_bonus_all_time = DB::table('bonus_rewards')->where('status','!=',3)
        ->select(DB::raw('sum(amt_usdt) as totalbonus,sum(remaining_usdt) as remainingbonus'))
        ->get()->first();
        
    $total_club_all_time = DB::table('club_incomes')
        ->select(DB::raw('sum(amt_usdt) as totalclub,sum(remaining_usdt) as remainingclub'))
        ->get()->first();
        
    $total_achievement_all_time = DB::table('achievement_incomes')
        ->select(DB::raw('sum(amount) as totallifetime,sum(remaining) as remaininglifetime'))
        ->get()->first();

    $unpaid_withdraw_all_time = ($total_roi_all_time->remainingroi ?? 0) + ($total_level_all_time->remaininglevel ?? 0) + ($total_development_all_time->remaininglevel ?? 0) + ($total_bonus_all_time->remainingbonus ?? 0) + ($total_club_all_time->remainingclub ?? 0);

    $total_revenue_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', '!=', 'Admin Topup']
        ])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $wallet_revenue_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '0'],
            ['transaction_details.paymentstatus', 2],
            ['transaction_infos.transaction_hash', 'Admin Deposit']
        ])
        ->join('transaction_infos', 'transaction_infos.txnid', '=', 'transaction_details.id')
        ->select(DB::raw('sum(amountusdt) as totalrevenue'))
        ->get()->first();

    $total_withdraw_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 2]
        ])
        ->select(DB::raw('sum(amountusdt) as totalwithdraw'))
        ->get()->first();

    $pending_withdraw_all_time = DB::table('transaction_details')->where([
            ['transaction_details.txntype', '1'],
            ['transaction_details.txndesc', 'Withdrawal'],
            ['transaction_details.paymentstatus', 1]
        ])
        ->select(DB::raw('sum(amountusdt) as pendingwithdraw'))
        ->get()->first();

    $total_loan_all_time = DB::table('loan_details')
        ->select(DB::raw('sum(amount) as totalloan,sum(remaining) as remainingloan'))
        ->get()->first();
        
    // Handle cases where no records are found for all-time queries
    $basic_revenue_all_time = $basic_revenue_all_time ?? (object)['basicrevenue' => 0];
    $loan_revenue_all_time = $loan_revenue_all_time ?? (object)['loanrevenue' => 0];
    $zero_pin_all_time = $zero_pin_all_time ?? (object)['amountusdt' => 0];
    $silver_pin_all_time = $silver_pin_all_time ?? (object)['amountusdt' => 0];
    $total_roi_all_time = $total_roi_all_time ?? (object)['totalroi' => 0, 'remainingroi' => 0];
    $total_level_all_time = $total_level_all_time ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $total_development_all_time = $total_development_all_time ?? (object)['totallevel' => 0, 'remaininglevel' => 0];
    $total_bonus_all_time = $total_bonus_all_time ?? (object)['totalbonus' => 0, 'remainingbonus' => 0];
    $total_club_all_time = $total_club_all_time ?? (object)['totalclub' => 0, 'remainingclub' => 0];
    $total_achievement_all_time = $total_achievement_all_time ?? (object)['totallifetime' => 0, 'remaininglifetime' => 0];
    $total_revenue_all_time = $total_revenue_all_time ?? (object)['totalrevenue' => 0];
    $wallet_revenue_all_time = $wallet_revenue_all_time ?? (object)['totalrevenue' => 0];
    $total_withdraw_all_time = $total_withdraw_all_time ?? (object)['totalwithdraw' => 0];
    $pending_withdraw_all_time = $pending_withdraw_all_time ?? (object)['pendingwithdraw' => 0];
    $total_loan_all_time = $total_loan_all_time ?? (object)['totalloan' => 0, 'remainingloan' => 0];


    // Add all-time data
    $rdata['total_member_all_time'] = $total_member_all_time;
    $rdata['total_paid_all_time'] = $total_paid_all_time;
    $rdata['total_unpaid_all_time'] = $total_unpaid_all_time;
    $rdata['basic_revenue_all_time'] = $basic_revenue_all_time;
    $rdata['loan_revenue_all_time'] = $loan_revenue_all_time;
    $rdata['zero_pin_all_time'] = $zero_pin_all_time;
    $rdata['silver_pin_all_time'] = $silver_pin_all_time;
    $rdata['total_roi_all_time'] = $total_roi_all_time;
    $rdata['total_level_all_time'] = $total_level_all_time;
    $rdata['total_development_all_time'] = $total_development_all_time;
    $rdata['total_bonus_all_time'] = $total_bonus_all_time;
    $rdata['total_club_all_time'] = $total_club_all_time;
    $rdata['total_achievement_all_time'] = $total_achievement_all_time;
    $rdata['unpaid_withdraw_all_time'] = $unpaid_withdraw_all_time;
    $rdata['total_revenue_all_time'] = $total_revenue_all_time;
    $rdata['wallet_revenue_all_time'] = $wallet_revenue_all_time;
    $rdata['total_withdraw_all_time'] = $total_withdraw_all_time;
    $rdata['pending_withdraw_all_time'] = $pending_withdraw_all_time;
    $rdata['total_loan_all_time'] = $total_loan_all_time;

 

    $rdata['price'] = $price->price;

    return view('control.dashboardalltime')->with('data', $rdata);
}




}