<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use DB;
use Session;
use App\Http\Controllers\SupportQueryController;

class AccountDepositController extends Controller
{
    //Admin
    public function adminUSDTPage(){
        if(Session::get('user.licence')!=3){
            return redirect()->back()->with('warning','Something went wrong');
        }
        return view('control.admintxndeposit')->with('user',null);
    }
    public function findUser(Request $request){
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
                ->select('users.email as email','users.uuid as userid','users.usersname as name')
                ->selectRaw('(user_details.id +'.\Session::get('logtime').') as id')
                ->first();
        return view('control.admintxndeposit')->with('user',$user);
    }

    public function transferUsdtToAdmin(Request $request){
        if(Session::get('user.licence')!=3){
            return redirect()->back()->with('warning','Something went wrong');
        }
        $validator=Validator::make($request->all(),[
            'honeypotu' =>['required','numeric'],
            'email'  =>  ['required','email','exists:users'],
            'uuid'  =>  ['required','string','exists:users'],
            'amount'    =>  ['required','numeric'],
            'comment'    =>  ['required','string', 'regex:/^[a-zA-Z0-9 ]+$/'],
            'password'  =>  ['required']
        ]);
        if($validator->fails()){
            return redirect()->to('/Main/AdminUSDTuser')->with('errors',$validator->errors());
        }
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
                    "amountsftc"  => ($request->amount/$profileStore->price),
                    "amountusdt"  => $request->amount,
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
                    "payment_addr"  =>  'Admin Deposit',
                    "transaction_hash"  =>  'Admin Deposit',
                    "contract_addr" =>$request->comment,
                    "amount"  =>  $request->amount,/*($request->currency=='usdt')?$request->amountusdtb:$request->styb*/
                    "txn_status"  =>  2,
                ]);
                $promotionalAdd=\App\AccountDeposit::firstOrNew([
                    'userid'    =>  $user->id,
                ]);
                if(!is_null($promotionalAdd->amount)){
                  $amt=Crypt::decrypt($promotionalAdd->amount)+($request->amount/*/$profileStore->price*/);
                }else{
                  $amt=($request->amount/*/$profileStore->price*/);
                }
                $promotionalAdd->amount=(Crypt::encrypt($amt));
                $promotionalAdd->save();
                $walletTransferEntry=\App\WalletTransfer::insertGetId([
                  'userid'  =>  $user->id,
                  'txnid'  =>  $insertWalletRequest,
                  'fromWallet'  =>  'deposite',
                  'toWallet'  =>  'wallet',
                  'amount'  =>  ($request->amount/*/$profileStore->price*/),
                  'release_date'    => date('Y-m-d'),
                   "created_at"  => now(),
                ]);

                $sendMail=new SupportQueryController();
                $userdt=\App\UserDetails::where('id',$user->id)->first();
                try{
                    $details['email']=$userdt->user()->email;
                    $details['subject']='Your deposit confirmed at Cyera AI';
                    $details['view']='depositmail';
                    $details['amount']=$request->amount;
                    $details['userid']=$userdt->user()->uuid;
                    $status=$sendMail->sendMailgun($details);
                }catch(Exception $e){
                    \Log::info('Error in sending Topupmail for userid '.$user->id);
                    \Log::info($e->messages());
                }
                
                return redirect()->to('/Main/AdminUSDTuser')->with('success','Wallet Successfully Transffered.');
            }else{
                return redirect()->to('/Main/AdminUSDTuser')->with('warning','There is some error . Please Try again.');
            }
        }
        return redirect()->back()->with('warning','Password did not match.'); 
    }

    public function reportAdminUsdt(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $topupuser=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Deposit']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->join('user_details','transaction_details.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->join('user_details as pdb','transaction_details.paidby','=','pdb.id')
        ->join('users as pdby','pdb.userid','=','pdby.id')
        ->select('transaction_details.id as id', 'users.email', 'users.uuid as userid', 'users.usersname', 'transaction_details.amountsftc', 'transaction_details.amountusdt', 'pdby.usersname as paidbyname', 'pdby.email as paidbyid', 'transaction_details.comments', 'transaction_details.created_at', 'transaction_infos.contract_addr as cmnt')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Confirmed" when transaction_details.paymentstatus=3 then "Failed" when transaction_details.paymentstatus=4 then "Failed" when transaction_details.paymentstatus=5 then "Failed" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "status-pending" when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" when transaction_details.paymentstatus=3 then "status-cancelled" when transaction_details.paymentstatus=4 then "status-cancelled" when transaction_details.paymentstatus=5 then "status-cancelled" end as statusclass')
        ->orderByRaw('transaction_details.id DESC')
        ->get();
        $sumamount=DB::table('transaction_details')->where([['transaction_details.txntype','0'],['transaction_details.txndesc','Wallet Deposite'],['transaction_details.paymentstatus',2],['transaction_infos.transaction_hash','Admin Deposit']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select(DB::raw('sum(transaction_details.amountsftc) as amount,sum(amountusdt) as amountusdt'))
        ->get()->first();
        /*dd($topupuser);*/
        return view('control.adminusdthistory')->with('topup',$topupuser)->with('sumamount',$sumamount);
    }

    public function userWalletBalance(){
        $walletbalance=DB::table('account_deposits')
        ->join('user_details','account_deposits.userid','=','user_details.id')
        ->join('users','user_details.userid','=','users.id')
        ->select('account_deposits.amount', 'users.email', 'users.uuid', 'users.usersname')
        ->orderByRaw('account_deposits.id DESC')
        ->get();
        $encryptedAmounts = DB::table('account_deposits')->pluck('amount');
        $sumamount = $encryptedAmounts->sum(function ($encrypted) {
            return Crypt::decrypt($encrypted);
        });
        /*dd($sumamount);*/
        return view('control.walletbalanceuser')->with('walletbalance',$walletbalance)->with('sumamount',$sumamount);
    }

    
}
