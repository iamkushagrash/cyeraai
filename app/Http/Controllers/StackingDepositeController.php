<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class StackingDepositeController extends Controller
{
    public function showAdminDepositePage(){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $availbleStfc=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        /*$stackingPeriod=\App\PlanDetails::where('status',1)->selectRaw('id *'.(pow(51, 3)).' as  id,cps,planname,amount as amount')->get();*/
        $price=\App\ProfileStore::where('id',1)->first();
        return view('control.admintbogusdeposit')->with('balance',$availbleStfc)/*->with('stacking',$stackingPeriod)*/->with('user',null)->with('bhav',$price);
    }


    public function findUserBogTopup(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $datareg=$this->findUserName($request->email);
        $regex=['required','exists:users,uuid'];
        $validator=Validator::make($request->all(),[
            'email'  =>  $regex,
        ])->validate();
        
        $user=\App\User::where($datareg['type'], $request->email)
                ->join('user_details','users.id','=','user_details.userid')
                ->select('users.email as email','users.uuid as userid','users.usersname as name','user_details.id as uid')
                ->selectRaw('(user_details.id +'.\Session::get('logtime').') as id')
                ->first();
        $availbleStfc=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        $stackingPeriod=\App\StackingDetail::where('status',1)->selectRaw('id *'.(pow(51, 3)).' as id,cps,min_amount as amount')->first();
        $price=\App\ProfileStore::where('id',1)->first();

        $userDetail=\App\UserDetails::where('id',$user->uid)->first();
        $activeplan=$userDetail->stackingDeposite()->get();
        
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining>0){
            return redirect()->back()->with('warning','User have an acive loan please repay it first.');
        }  

        return view('control.admintbogusdeposit')->with('balance',$availbleStfc)->with('stacking',$stackingPeriod)->with('user',$user)->with('bhav',$price)->with('activeplan',$activeplan);
    }

    public function transferBogTopupToAdmin(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        set_time_limit(0);
        if (isset($request->amount)) {
            if ($request->amount%100!=0) {
                return redirect()->to('/Main/AdminSystemTopup')->with('warning','Please enter amount in multiple of 100$');
            }
        }else{
            return redirect()->to('/Main/AdminSystemTopup')->with('warning','Please enter amount');
        }
        $price=\App\ProfileStore::where('id',1)->first();
        $quantity='min:'.(100);
        $validator=Validator::make($request->all(),[
            'honeypotu' =>['required','numeric'],
            'uuid'  =>  ['required','string','exists:users'],
            'amount'    =>  ['required','numeric',$quantity],
            /*'plan'       =>   ['required','numeric'],*/
            'password'  =>  ['required']
        ]);
        if($validator->fails()){
            return redirect()->to('/Main/AdminSystemTopup')->with('errors',$validator->errors());
        }
        if($request->amount>1001){
            return redirect('/Main/AdminSystemTopup')->with('warning','amount should be less than of 1000');
        }
        /*$plan=\App\StackingDetail::where([['min_amount','<=',$request->amount],['max_amount','>=',$request->amount]])->first();
        if(is_null($plan)){
            return redirect()->to('/Main/AdminSystemTopup')->with('warning','Select a proper plan.');
        }*/
        //$plan=\App\PlanDetails::where('id',($request->plan/pow(51, 3)))->first();
        $plan=\App\StackingDetail::where('status',1)->first();
        if(is_null($plan)){
            return redirect()->to('/Main/AdminSystemTopup')->with('warning','Please select proper plan.');
        }
        $amount=$request->amount;
        $adminDetails=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(\Hash::check($request->password, $adminDetails->user()->password)){
            $user=\App\User::where('uuid',$request->uuid)
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id  as id')
            ->first();
            if($user->id==($request->honeypotu-\Session::get('logtime'))){
                $profileStore=\App\ProfileStore::all()->first();
                $insertWalletRequest=\App\TransactionDetail::insertGetId([
                    "userid"  => $user->id,
                    "txntype"  => 0,
                    "amountsftc"  => $amount/$price->price,
                    "amountusdt"  => $amount,
                    "remaining"  => 0, /*($request->currency=='usdt')?$request->amountusdtb:$request->styb*/
                    "paymentstatus"  => 2,
                    "txndesc"  => "Wallet Deposite",
                    "comments"  => 'wallet',
                    "planid"  => 0,
                    "currency"  => 'usdt',
                    "paidby"  => \Session::get('user.id'),
                    "created_at"  => now(),
                    "release_date"  =>date('Y-m-d'),
                ]);
                $insTxnInfo=\App\TransactionInfo::create([
                    "txnid"  =>  $insertWalletRequest,
                    "payment_addr"  =>  'Admin Topup',
                    "transaction_hash"  =>  'Admin Topup',
                    "contract_addr" =>'Admin Topup',
                    "amount"  =>  $amount,/*($request->currency=='usdt')?$request->amountusdtb:$request->styb*/
                    "txn_status"  =>  2,
                ]);
                $promotionalAdd=\App\AccountDeposit::firstOrNew([
                    'userid'    =>  $user->id,
                ]);
                if(!is_null($promotionalAdd->amount)){
                  $amt=Crypt::decrypt($promotionalAdd->amount);
                }else{
                  $amt=(0);
                }
                $promotionalAdd->amount=(Crypt::encrypt($amt));
                $promotionalAdd->save();
                $walletTransferEntry=\App\WalletTransfer::insertGetId([
                  'userid'  =>  $user->id,
                  'txnid'  =>  $insertWalletRequest,
                  'fromWallet'  =>  'admin topup',
                  'toWallet'  =>  'wallet',
                  'amount'  =>  ($amount),
                  'fromUser'  => \Session::get('user.id'),
                  'created_at'  =>  date('Y-m-d H:i:s'),
                  'release_date'    => date('Y-m-d'),
                ]);
                $insWalletEntry=\App\WalletTransfer::create([
                    'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                    'txnid'  =>  0,
                    'fromWallet'  =>  'wallet',
                    'toWallet'  =>  'basic',
                    'amount'  =>  ($amount),
                    'fromUser'  => $user->id,
                    'created_at'  =>  date('Y-m-d H:i:s'),
                    'release_date'  =>  date('Y-m-d'),
                ]);
                $insertWallet=\App\StackingDeposite::create([
                    'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                    'txnid'  =>  $insWalletEntry->id,
                    'amount'  =>  ($amount/$price->price),
                    'usdt'  => ($amount),
                    'capamount' =>  Crypt::encrypt($amount*5),
                    'planid'  =>  $plan->id,
                    'status'    =>  3,
                    'roidouble' => 1,
                    'created_at'  =>  date('Y-m-d H:i:s'),
                    'istatus'   =>   1, 
                    'staketype'   =>   3, 
                ]);

                $userUpdate=\App\UserDetails::where('id',$request->honeypotu-\Session::get('logtime'));
                $userStatus=$userUpdate->first()->userstate;
                $userUpdate->increment('userstate');
                $userUpdate->increment('current_self_investment',($amount));
                $userUpdate->increment('total_self_investment',($amount));
                $userUpdate->update(['userstatus'=>1,'capping'=>0,'roi_status'=>1,]);

                $sendMail=new SupportQueryController();
                try{
                    $details['email']=$userUpdate->first()->user()->email;
                    $details['subject']='You have a top-up now.';
                    $details['view']='planactivationmail';
                    $details['amount']=$request->amount;
                    $details['userid']=$userUpdate->first()->user()->uuid;
                    $status=$sendMail->sendMailgun($details);
                }catch(Exception $e){
                    \Log::info('Error in sending Topupmail for userid '.$userUpdate->first()->userid);
                    \Log::info($e->messages());
                }
                    
                return redirect()->to('/Main/AdminSystemTopup')->with('success','User Topuped Successfully.');
            }else{
                return redirect()->to('/Main/AdminSystemTopup')->with('warning','There is some error . Please Try again.');
            }
        }
        return redirect()->back()->with('warning','Password did not match.'); 
    }



    public function reportAdminSystemTopup(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $topupuser=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Topup'],['transaction_infos.payment_addr','Admin Topup']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->join('user_details','transaction_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->join('user_details as pdb','transaction_details.paidby','=','pdb.id')
        ->join('users as pdby','pdb.userid','=','pdby.id')
        ->select('transaction_details.id as id', 'users.email', 'users.uuid as userid', 'users.usersname', 'transaction_details.amountsftc as amount', 'transaction_details.amountusdt', 'pdby.usersname as paidbyname', 'pdby.email as paidbyid', 'transaction_details.comments', 'transaction_details.created_at')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Failed" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Failed" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "status-pending" when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" when transaction_details.paymentstatus=3 then "status-cancelled" when transaction_details.paymentstatus=4 then "status-cancelled" when transaction_details.paymentstatus=5 then "status-cancelled" end as statusclass')
        ->orderByRaw('transaction_details.id DESC')
        ->get();
        $sumamount=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Topup'],['transaction_infos.payment_addr','Admin Topup']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(transaction_details.amountsftc) as amountsftc,sum(amountusdt) as amountusdt'))
        ->get()->first();
        /*dd($topupuser);*/
        return view('control.admintbogusdeposithistory')->with('topup',$topupuser)->with('sumamount',$sumamount);
    }




    public function showAdminROItopPage(){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $availbleStfc=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        /*$stackingPeriod=\App\PlanDetails::where('status',1)->selectRaw('id *'.(pow(51, 3)).' as  id,cps,planname,amount as amount')->get();*/
        $price=\App\ProfileStore::where('id',1)->first();
        return view('control.adminroideposit')->with('balance',$availbleStfc)/*->with('stacking',$stackingPeriod)*/->with('user',null)->with('bhav',$price);
    }


    public function findUserROITopup(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $datareg=$this->findUserName($request->email);
        $regex=['required','exists:users,uuid'];
        $validator=Validator::make($request->all(),[
            'email'  =>  $regex,
        ])->validate();
        
        $user=\App\User::where($datareg['type'], $request->email)
                ->join('user_details','users.id','=','user_details.userid')
                ->select('users.email as email','users.uuid as userid','users.usersname as name','user_details.id as uid')
                ->selectRaw('(user_details.id +'.\Session::get('logtime').') as id')
                ->first();
        $availbleStfc=\App\AccountDeposit::where('userid',\Session::get('user.id'))->first();
        $stackingPeriod=\App\StackingDetail::where('status',1)->selectRaw('id *'.(pow(51, 3)).' as id,cps,min_amount as amount')->first();
        $price=\App\ProfileStore::where('id',1)->first();

        $userDetail=\App\UserDetails::where('id',$user->uid)->first();
        $activeplan=$userDetail->stackingDeposite()->get();
        if(!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining>0){
            return redirect()->back()->with('warning','User have an acive loan please repay it first.');
        }  
        
        return view('control.adminroideposit')->with('balance',$availbleStfc)->with('stacking',$stackingPeriod)->with('user',$user)->with('bhav',$price)->with('activeplan',$activeplan);
    }

    public function transferROITopupToAdmin(Request $request){
        if(Session::get('user.licence')==2){
            return redirect()->back()->with('warning','Something went wrong');
        }
        set_time_limit(0);
        if (isset($request->amount)) {
            if ($request->amount%100!=0) {
                return redirect()->to('/Main/AdminROITopup')->with('warning','Please enter amount in multiple of 100$');
            }
        }else{
            return redirect()->to('/Main/AdminROITopup')->with('warning','Please enter amount');
        }
        $price=\App\ProfileStore::where('id',1)->first();
        $quantity='min:'.(100);
        $validator=Validator::make($request->all(),[
            'honeypotu' =>['required','numeric'],
            'uuid'  =>  ['required','string','exists:users'],
            'amount'    =>  ['required','numeric',$quantity],
            /*'plan'       =>   ['required','numeric'],*/
            'password'  =>  ['required']
        ]);
        if($validator->fails()){
            return redirect()->to('/Main/AdminROITopup')->with('errors',$validator->errors());
        }
        if($request->amount>10001){
            return redirect('/Main/AdminROITopup')->with('warning','amount should be less than of 10000');
        }
        /*$plan=\App\StackingDetail::where([['min_amount','<=',$request->amount],['max_amount','>=',$request->amount]])->first();
        if(is_null($plan)){
            return redirect()->to('/Main/AdminSystemTopup')->with('warning','Select a proper plan.');
        }*/
        //$plan=\App\PlanDetails::where('id',($request->plan/pow(51, 3)))->first();
        $plan=\App\StackingDetail::where('status',1)->first();
        if(is_null($plan)){
            return redirect()->to('/Main/AdminROITopup')->with('warning','Please select proper plan.');
        }
        $amount=$request->amount;
        $adminDetails=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        if(\Hash::check($request->password, $adminDetails->user()->password)){
            $user=\App\User::where('uuid',$request->uuid)
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id  as id')
            ->first();
            if($user->id==($request->honeypotu-\Session::get('logtime'))){
                $profileStore=\App\ProfileStore::all()->first();
                $insertWalletRequest=\App\TransactionDetail::insertGetId([
                    "userid"  => $user->id,
                    "txntype"  => 0,
                    "amountsftc"  => $amount/$price->price,
                    "amountusdt"  => $amount,
                    "remaining"  => 0, /*($request->currency=='usdt')?$request->amountusdtb:$request->styb*/
                    "paymentstatus"  => 2,
                    "txndesc"  => "Wallet Deposite",
                    "comments"  => 'wallet',
                    "planid"  => 0,
                    "currency"  => 'usdt',
                    "paidby"  => \Session::get('user.id'),
                    "created_at"  => now(),
                    "release_date"  =>date('Y-m-d'),
                ]);
                $insTxnInfo=\App\TransactionInfo::create([
                    "txnid"  =>  $insertWalletRequest,
                    "payment_addr"  =>  'Admin ROI',
                    "transaction_hash"  =>  'Admin Topup',
                    "contract_addr" =>'Admin Topup',
                    "amount"  =>  $amount,/*($request->currency=='usdt')?$request->amountusdtb:$request->styb*/
                    "txn_status"  =>  2,
                ]);
                $promotionalAdd=\App\AccountDeposit::firstOrNew([
                    'userid'    =>  $user->id,
                ]);
                if(!is_null($promotionalAdd->amount)){
                  $amt=Crypt::decrypt($promotionalAdd->amount);
                }else{
                  $amt=(0);
                }
                $promotionalAdd->amount=(Crypt::encrypt($amt));
                $promotionalAdd->save();
                $walletTransferEntry=\App\WalletTransfer::insertGetId([
                  'userid'  =>  $user->id,
                  'txnid'  =>  $insertWalletRequest,
                  'fromWallet'  =>  'admin topup',
                  'toWallet'  =>  'wallet',
                  'amount'  =>  ($amount),
                  'fromUser'  => \Session::get('user.id'),
                  'created_at'  =>  date('Y-m-d H:i:s'),
                  'release_date'    => date('Y-m-d'),
                ]);
                $insWalletEntry=\App\WalletTransfer::create([
                    'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                    'txnid'  =>  0,
                    'fromWallet'  =>  'wallet',
                    'toWallet'  =>  'basic',
                    'amount'  =>  ($amount),
                    'fromUser'  => $user->id,
                    'created_at'  =>  date('Y-m-d H:i:s'),
                    'release_date'  =>  date('Y-m-d'),
                ]);
                $insertWallet=\App\StackingDeposite::create([
                    'userid'  =>  $request->honeypotu-\Session::get('logtime'),
                    'txnid'  =>  $insWalletEntry->id,
                    'amount'  =>  ($amount/$price->price),
                    'usdt'  => ($amount),
                    'capamount' =>  Crypt::encrypt($amount*5),
                    'planid'  =>  $plan->id,
                    'status'    =>  4,
                    'roidouble' => 1,
                    'created_at'  =>  date('Y-m-d H:i:s'),
                    'istatus'   =>   1, 
                    'levelincome'   =>   0, 
                    'staketype'   =>   4, 
                ]);

                $userUpdate=\App\UserDetails::where('id',$request->honeypotu-\Session::get('logtime'));
                $userStatus=$userUpdate->first()->userstate;
                $userUpdate->increment('userstate');
                $userUpdate->increment('current_self_investment',($amount));
                $userUpdate->increment('total_self_investment',($amount));
                $userUpdate->update(['userstatus'=>1,'capping'=>0,'roi_status'=>1,]);

                $sendMail=new SupportQueryController();
                try{
                    $details['email']=$userUpdate->first()->user()->email;
                    $details['subject']='You have a top-up now.';
                    $details['view']='planactivationmail';
                    $details['amount']=$request->amount;
                    $details['userid']=$userUpdate->first()->user()->uuid;
                    $status=$sendMail->sendMailgun($details);
                }catch(Exception $e){
                    \Log::info('Error in sending Topupmail for userid '.$userUpdate->first()->userid);
                    \Log::info($e->messages());
                }
                    
                return redirect()->to('/Main/AdminROITopup')->with('success','User Topuped Successfully.');
            }else{
                return redirect()->to('/Main/AdminROITopup')->with('warning','There is some error . Please Try again.');
            }
        }
        return redirect()->back()->with('warning','Password did not match.'); 
    }


    public function reportAdminROITopup(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $topupuser=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Topup'],['transaction_infos.payment_addr','Admin ROI']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->join('user_details','transaction_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->join('user_details as pdb','transaction_details.paidby','=','pdb.id')
        ->join('users as pdby','pdb.userid','=','pdby.id')
        ->select('transaction_details.id as id', 'users.email', 'users.uuid as userid', 'users.usersname', 'transaction_details.amountsftc as amount', 'transaction_details.amountusdt', 'pdby.usersname as paidbyname', 'pdby.email as paidbyid', 'transaction_details.comments', 'transaction_details.created_at')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Failed" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Failed" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "status-pending" when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" when transaction_details.paymentstatus=3 then "status-cancelled" when transaction_details.paymentstatus=4 then "status-cancelled" when transaction_details.paymentstatus=5 then "status-cancelled" end as statusclass')
        ->orderByRaw('transaction_details.id DESC')
        ->get();
        $sumamount=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Topup'],['transaction_infos.payment_addr','Admin ROI']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(transaction_details.amountsftc) as amountsftc,sum(amountusdt) as amountusdt'))
        ->get()->first();
        /*dd($topupuser);*/
        return view('control.adminroideposithistory')->with('topup',$topupuser)->with('sumamount',$sumamount);
    }






}
