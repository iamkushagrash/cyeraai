<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\WithdrawInfoController;

class TransactionDetailController extends Controller
{
    //User
    public function showTransactionPage(){
        //dd(Crypt::encrypt('0x7B5691917CAE40174BD636D84B3E36AFbCCD2F01'));
        $detail=\App\ProfileStore::where('id',1)->first();
        $user=\App\UserDetails::where('id',\Session::get('user.id'))->first();
        
        $transaction=\App\TransactionDetail::where([['paymentstatus','>',0],['txntype',0]])->get();
        return view('user.depositusdt')->with('detail',$detail)->with('user',$user)->with('transaction',$transaction);
        
    }

    public function submitTransaction(Request $request){
        if($request->currency=='usdt')
            $regex='regex:/^[a-z0-9]{64}$/u';
        else
            $regex='regex:/^0x[a-fA-F0-9]{64}$/u';
        $vali=Validator::make($request->all(), [
            'honeypot'  =>  ['nullable','numeric'],
            'currency'  =>  ['required','string'],
            'amountusdt'  =>  ['required','numeric'],
            'mwt'  =>   ['nullable','numeric' ],
            'cai'  =>   ['nullable','numeric' ],
            'txnhash'  =>  [ 'string' , $regex,'unique:transaction_infos,transaction_hash'],
        ])->validate();
        $tokenAmount = !is_null($request->cai) ? $request->cai : $request->mwt;
        $selTxn=\App\TransactionInfo::where('id',($request->honeypot-\Session::get('logtime')))->first();
        if($request->amountusdt<10 /*&& $request->currency!='usdt'*/){
            return redirect()->back()->with('warning','Amount of USDT should be greater than 10.');
        }
        if(is_null($request->honeypot) || $request->honeypot< \Session::get('logtime')){
            //$this->userIssueReport('payload','Deposite');
        }elseif($request->honeypot>\Session::get('logtime') && $request->honeypot<\Session::get('logtime')){
            if(is_null($selTxn)){
                //$this->userIssueReport('payload','Remaining Deposite');
                \Log::info('User is tempering with payload user id is '.\Session::get('user.id'));
                $userDetailsUpd=\App\User::where('email',\Session::get('user.email'))->update([
                    'permission'  =>  0,
                ]);
                \Auth::logout();
                return redirect('/login')->with('warning','Your ID is blocked. please contact admin.');
            }else{
                $usrPaydetail=\App\TransactionDetail::where('id',($selTxn->txnid))->first();
                if(!($usrPaydetail->remaining==$tokenAmount)){
                    //$this->userIssueReport('remaining amount','Remaining Deposite');
                    \Log::info('User is tempering remaining amount in Remaining Deposite. Txn Id is '.$selTxn->txnid.' user id is '.\Session::get('user.id'));
                    $userDetailsUpd=\App\User::where('email',\Session::get('user.email'))->update([
                        'permission'  =>  0,
                    ]);
                    \Auth::logout();
                    return redirect('/login')->with('warning','Your ID is blocked. please contact admin.');
                }
            }
        }
        if(!is_null($request->honeypot) && $request->amountusdt>=10 && $request->honeypot==\Session::get('logtime')){
            $assetDetail=\App\AssetDetail::where('userid',\Session::get('user.id'))->first();
            $insertWalletRequest=\App\TransactionDetail::insertGetId([
                "userid"  => \Session::get('user.id'),
                "txntype"  => 0,
                "amountsftc"  => $tokenAmount,
                "amountusdt"  => $request->amountusdt,
                "remaining"  => ($request->currency=='usdt' || $request->currency=='usdtbep20')?$request->amountusdt:$tokenAmount, 
                "paymentstatus"  => 1,
                "txndesc"  => "User Deposit",
                "comments"  => 'wallet',
                "planid"  => 0,
                "currency"  => $request->currency,
                "paidby"  => \Session::get('user.id'),
                "created_at"  => now(),
                "release_date"  =>date('Y-m-d'),
            ]);
            $profileDetails=\App\ProfileStore::all()->first();
            $insTxnInfo=\App\TransactionInfo::create([
                "txnid"  =>  $insertWalletRequest,
                "payment_addr"  =>  ($request->currency=='usdt')?Crypt::decrypt($profileDetails->usdt):(($request->currency=='usdtbep20')?Crypt::decrypt($profileDetails->usdtbep20):Crypt::decrypt($profileDetails->sftc)),//\Illuminate\Support\Facades\Crypt::decrypt($assetDetail->depositaddr)
                "transaction_hash"  =>  $request->txnhash,
                "contract_addr" =>($request->currency=='usdt')?$profileDetails->usdt_contract_addr:(($request->currency=='usdtbep20')?$profileDetails->usdtbep20_contract_addr:$profileDetails->sftc_contract_addr),
                "amount"  =>  ($request->currency=='usdt' || $request->currency=='usdtbep20')?$request->amountusdt:$tokenAmount,
                "txn_status"  =>  1,
            ]);
        }else{
            $selTxn=\App\TransactionInfo::where('id',($request->honeypot-\Session::get('logtime')))->first();
            $updTxn=\App\TransactionInfo::where('id',($request->honeypot-\Session::get('logtime')))->update([
                'txn_status'=> 1,'transaction_hash' => $request->txnhash ,
            ]);
            $updTxn=\App\TransactionDetail::where('id',($selTxn->txnid))->update([
                'paymentstatus'=> 1,
            ]);
        }
        
        return back()->with('success','Request submitted successfully.');
    }




    //Admin
    public function userWithdrawreq(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        }
        $adminwithreq=DB::table('transaction_details')->where([['transaction_details.txntype',1],['b_status',3],['transaction_details.paymentstatus',1]])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        //->join('asset_details','user_details.id','=','asset_details.userid')
        ->select('transaction_details.id as txnid','users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'payee_addr as withdrawtype', 'currency as currency', 'transaction_details.created_at as txndate')
        ->selectRaw('"Pending" as status')
        ->selectRaw('"status-pending" as statusclass')
        ->orderBy('transaction_details.id', 'desc')
        ->get();
        $sumtotaldb=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',1],['b_status',3]])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->selectRaw('SUM(amountusdt) as amountusdt')
        ->first();
        $sumtotal = [
            'amountusdt' => $sumtotaldb->amountusdt,
        ];
        //dd($sumtotal);
        return view('control.withdrawrequests')->with('requests',$adminwithreq)->with('sumtotal',$sumtotal);
    }

    public function WithdrawRequestsExcel(){
        $adminwithreq=DB::table('transaction_details')->where([['txntype',1],['b_status',3],['paymentstatus',1]])
        /*->where(function($q) {
             $q->where('transaction_details.paymentstatus', 0)
               ->orWhere('transaction_details.paymentstatus', 1);
         })*/
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        //->join('asset_details','user_details.id','=','asset_details.userid')
        ->select(/*'transaction_details.id as txnid',*/'users.usersname as usersname','users.uuid as uuid','users.email as email', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "status-pending" when transaction_details.paymentstatus=1 then "status-pending" end as statusclass,sum(transaction_details.amountsftc) as amountsftc,sum(transaction_details.amountusdt) as amountusdt,sum(transaction_details.deduction) as deduction,sum(transaction_details.net_amount) as net_amount')
        ->orderBy('transaction_details.id', 'asc')
        ->groupBy('payment_addr')
        ->get();
        $sumtotal=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',1],['b_status',3]])
        /*->where(function($q) {
             $q->where('transaction_details.paymentstatus', 0)
               ->orWhere('transaction_details.paymentstatus', 1);
         })*/
        ->select(DB::raw('sum(amountsftc) as amountsftc,sum(amountusdt) as amountusdt'))
        ->get()->first();
        return view('control.withdrawexcel')->with('requests',$adminwithreq)->with('sumtotal',$sumtotal);
    }

    public function AdminwithdrawEdit($paymentid){
        $withdrawshow=DB::table('transaction_details')->where([['txntype',1],['transaction_details.id',$paymentid]])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->join('asset_details','user_details.id','=','asset_details.userid')
        ->select('transaction_details.id as txnid','users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.deduction as deduction', 'transaction_details.net_amount as net_amount', 'payment_addr as address', 'transaction_hash as txnhash', 'currency as currency', DB::raw('DATE_FORMAT(transaction_details.created_at ,"%Y-%m-%d")as txndate'))
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" when transaction_details.paymentstatus=3 then "Reject" end as status')
        ->get()->first();
        //dd($withdrawshow);
        return view('control.withdrawupdate')->with('withdata',$withdrawshow);
    }
    public function AdminwithdrawUpdateone(Request $request){
        Validator::make($request->all(), [
            'paymentid'      => ['required', 'numeric'],
            /*'amountsftc'      => ['required', 'numeric'],*/
            'amountusdt'      => ['required', 'numeric'],
            'txnhash'   => ['required', 'string','regex:/^[a-zA-Z0-9\s]|[^<>]+$/u'],
            'withstatus'  => ['required', 'string','regex:/^[a-zA-Z0-9\s]|[^<>]+$/u'],
        ])->validate();
        
        if($request->withstatus=='Pending'){
            $status=1;
        }elseif($request->withstatus=='Success'){
            $status=2;
        }elseif($request->withstatus=='Reject'){
            $status=3;
        }

        \DB::beginTransaction();
        try{

            if ($status==1 || $status==2) {

                $updpayment=DB::table('transaction_details')->where('id',$request->paymentid)->update([
                    'paymentstatus' => $status,
                    'remaining' => 0,
                    'paidby' => Session::get('user.id'),
                    ]);
                $updtxninfo=DB::table('transaction_infos')->where('txnid',$request->paymentid)->update([
                    'transaction_hash' => $request->txnhash,
                    'txn_status' => $status,
                    ]);
            }else{
                $updpayment=DB::table('transaction_details')->where('id',$request->paymentid)->update([
                    'paymentstatus' => $status,
                    'remaining' => 0,
                    'paidby' => Session::get('user.id'),
                    ]);
                $updtxninfo=DB::table('transaction_infos')->where('txnid',$request->paymentid)->update([
                    'transaction_hash' => $request->txnhash,
                    'txn_status' => $status,
                    ]);

                $withdrawInfo=\App\WithdrawInfo::where('txnid',$request->paymentid)->first();

                //get all cps for roll back
                $getAllCps=\App\CpsIncome::where('intxna',$request->paymentid)->orWhere('intxnb',$request->paymentid)->orderBy('id','desc')->get();
                $amt=$withdrawInfo->stacking;
                foreach($getAllCps as $cps){
                    $amount=($cps->intxna==$request->paymentid && $cps->intxnb==0)?$cps->amt_usdt-$cps->remaining_usdt:$amt;
                    $updateCPS=\App\CpsIncome::where('id',$cps->id)->update([
                        'remaining_usdt'  =>  \DB::raw('remaining_usdt+'.$amount),
                        ($cps->intxna==$request->paymentid && $cps->intxnb==0)?'intxna':'intxnb'   =>  0,
                    ]);
                    $amt-=$amount;
                }
                //get all level for roll back
                if($withdrawInfo->level>0){
                    $getAllLevel=\App\LevelIncome::where('intxna',$request->paymentid)->orWhere('intxnb',$request->paymentid)->orderBy('id','desc')->get();
                    $amt=$withdrawInfo->level;
                    foreach($getAllLevel as $level){
                        $amount=($level->intxna==$request->paymentid && $level->intxnb==0)?$level->amt_usdt-$level->remaining_usdt:$amt;
                        $updateLevel=\App\LevelIncome::where('id',$level->id)->update([
                            'remaining_usdt'  =>  \DB::raw('remaining_usdt+'.$amount),
                            ($level->intxna==$request->paymentid && $level->intxnb==0)?'intxna':'intxnb'   =>  0,
                        ]);
                        $amt-=$amount;
                    }
                }
                //get all bonus for roll back
                if($withdrawInfo->bonus>0){
                    $getAllFixed=\App\BonusReward::where('intxna',$request->paymentid)->orWhere('intxnb',$request->paymentid)->orderBy('id','desc')->get();
                    $amt=$withdrawInfo->bonus;
                    foreach($getAllFixed as $fixed){
                        $amount=($fixed->intxna==$request->paymentid && $fixed->intxnb==0)?$fixed->amt_usdt-$fixed->remaining_usdt:$amt;
                        $updateClub=\App\BonusReward::where('id',$fixed->id)->update([
                            'remaining_usdt'  =>  \DB::raw('remaining_usdt+'.$amount),
                            ($fixed->intxna==$request->paymentid && $fixed->intxnb==0)?'intxna':'intxnb'   =>  0,
                        ]);
                        $amt-=$amount;
                    }
                }
                //get all club for roll back
                if($withdrawInfo->club>0){
                    $getAllClub=\App\ClubIncome::where('intxna',$request->paymentid)->orWhere('intxnb',$request->paymentid)->orderBy('id','desc')->get();
                    $amt=$withdrawInfo->club;
                    foreach($getAllClub as $clubb){
                        $amount=($clubb->intxna==$request->paymentid && $clubb->intxnb==0)?$clubb->amt_usdt-$clubb->remaining_usdt:$amt;
                        $updateClub=\App\ClubIncome::where('id',$clubb->id)->update([
                            'remaining_usdt'  =>  \DB::raw('remaining_usdt+'.$amount),
                            ($clubb->intxna==$request->paymentid && $clubb->intxnb==0)?'intxna':'intxnb'   =>  0,
                        ]);
                        $amt-=$amount;
                    }
                }
                //get all lifetime for roll back
                if($withdrawInfo->salary>0){
                    $getAllSalary=\App\AchievementIncome::where('intxna',$request->paymentid)->orWhere('intxnb',$request->paymentid)->orderBy('id','desc')->get();
                    $amt=$withdrawInfo->salary;
                    foreach($getAllSalary as $salary){
                        $amount=($salary->intxna==$request->paymentid && $salary->intxnb==0)?$salary->amount-$salary->remaining:$amt;
                        $updateClub=\App\AchievementIncome::where('id',$salary->id)->update([
                            'remaining'  =>  \DB::raw('remaining+'.$amount),
                            ($salary->intxna==$request->paymentid && $salary->intxnb==0)?'intxna':'intxnb'   =>  0,
                        ]);
                        $amt-=$amount;
                    }
                }
            }
        }
        catch(Exception $e){
            \DB::rollback();
            \Log::info('Error for paymentid '.$request->paymentid. ' Error message is '.$e->getMessage());
            return redirect()->back()->with('warning','You have some issue with cancel. Please Try again.');
        }
        \DB::commit();
        
        return redirect()->back()->with(['success'=>'Withdrawal Updated Successfully.']);
    }

    public function AdminwithdrawUpdateGateway($paymentid){
        $withdrawentry=DB::table('transaction_details')->where([['txntype',1],['transaction_details.id',$paymentid]])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->select('transaction_details.id as txnid','users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.deduction as deduction', 'transaction_details.net_amount as net_amount', 'payment_addr as address', 'transaction_hash as txnhash', 'currency as currency', 'paymentstatus as paystatus')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" when transaction_details.paymentstatus=5 then "Cancel" end as status')
        ->get()->first();
        
        if (!is_null($withdrawentry)) {
            if ($withdrawentry->paystatus<2 && $withdrawentry->currency=='usdtbep20') {
                $withFunction=$this->withdrawApiUsdtAdmin($withdrawentry->address,round($withdrawentry->net_amount,2));
                //dd($withFunction,is_null($withFunction));
                if (!is_null($withFunction) && (!isset($withFunction->status) && (isset($withFunction->Status) && $withFunction->Status==200))) {
                    $updateTxn=\App\TransactionDetail::where('id',$withdrawentry->txnid)->update([
                        'paymentstatus'  =>  2,
                        'remaining'  =>  0,
                        'paidby' => Session::get('user.id'),
                        'updated_at'  =>  date('Y-m-d H:i:s'),
                    ]);
                    $updateTransactionInfo=\App\TransactionInfo::where('txnid',$withdrawentry->txnid)->update([
                        'transaction_hash'  =>  $withFunction->data->hash,
                    ]);

                    return redirect()->back()->with(['success'=>'Transaction Confirmed.']);

                } else{
                    return redirect()->back()->with(['warning'=>'Error in Gateway.']);
                }
            }else{
                return redirect()->back()->with(['warning'=>'Transaction Already Confirmed or failed.']);
            }
        }else{
            return redirect()->back()->with(['warning'=>'Transaction not found.']);
        }
        
        
    }

    public function withdrawApiUsdtAdmin($address,$amount){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://motomotaserver64sdsds.5kakar.com/api/withdraw',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => '{
              "recipientAddress": "'.$address.'",
              "amount": "'.$amount.'"
            }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
          /*CURLOPT_POSTFIELDS => 'recipientAddress='.$address.'&amount='.$amount,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),*/
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        \Log::info($response);

        //\Log::info(json_decode($response)->Status);
        return json_decode($response);

    }

    public function userWithdrawHistory(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
            $wdreport=DB::table('transaction_details')->where([['txntype',1],['transaction_details.paymentstatus',2],['transaction_infos.payment_addr','!=','Withdraw In MetaWallet']])
            ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
            ->join('user_details','user_details.id','=','transaction_details.userid')
            ->join('users','users.id','=','user_details.userid')
            ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
            //->join('asset_details','user_details.id','=','asset_details.userid')
            ->select('users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate', 'transaction_details.updated_at as updated', 'transaction_details.paidby as paidby')
            ->selectRaw('"Success" as status')
            ->selectRaw('"status-complete" as statusclass')
            ->orderBy('transaction_details.id', 'desc')
            ->get();
            $wdtotaldb=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',2],['transaction_infos.payment_addr','!=','Withdraw In MetaWallet']])
            ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
            ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
            ->selectRaw('SUM(amountusdt) as amountusdt')
            ->first();
            $wdtotal = [
                'amountusdt' => $wdtotaldb->amountusdt,
            ];
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
            $wdreport=DB::table('transaction_details')->where([['txntype',1],['transaction_details.paymentstatus',2],['transaction_infos.payment_addr','!=','Withdraw In MetaWallet']])
            ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
            ->join('user_details','user_details.id','=','transaction_details.userid')
            ->join('users','users.id','=','user_details.userid')
            ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
            //->join('asset_details','user_details.id','=','asset_details.userid')
            ->select('users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate', 'transaction_details.updated_at as updated', 'transaction_details.paidby as paidby')
            ->selectRaw('case when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" end as status')
            ->selectRaw('case when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" end as statusclass')
            ->orderBy('transaction_details.id', 'desc')
            ->get();
            $wdtotaldb=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',2],['transaction_infos.payment_addr','!=','Withdraw In MetaWallet']])
            ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
            ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
            ->selectRaw('SUM(amountusdt) as amountusdt')
            ->first();
            $wdtotal = [
                'amountusdt' => $wdtotaldb->amountusdt,
            ];
        }
        //dd($wdreport,$wdtotal);
        
        return view('control.withdrawhistory')->with('requests',$wdreport)->with('sumtotal',$wdtotal);
    }

    public function userWithdrawHistoryMetaWallet(Request $request){
        if($request->method()==="GET"){
            $fromDate=date('Y-m-d');
            $toDate=date('Y-m-d').' 23:59:59';
        $wdreport=DB::table('transaction_details')->where([['txntype',1],['transaction_details.paymentstatus',2],['transaction_details.comments','usdt']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        /*->join('asset_details','user_details.id','=','asset_details.userid')*/
        ->select('users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate', 'transaction_details.updated_at as updated')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" end as statusclass')
        ->orderBy('transaction_details.id', 'desc')
        ->get();
        $wdtotal=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',2],['transaction_details.comments','usdt']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->selectRaw('SUM(amountusdt) as amountusdt')
        ->first();
        }else{
            $fromDate=$request->fromdate;
            $toDate=$request->todate.' 23:59:59';
        $wdreport=DB::table('transaction_details')->where([['txntype',1],['transaction_details.paymentstatus',2],['transaction_details.comments','usdt']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        /*->join('asset_details','user_details.id','=','asset_details.userid')*/
        ->select('users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate', 'transaction_details.updated_at as updated')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "Pending" when transaction_details.paymentstatus=2 then "Success" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=1 then "status-pending" when transaction_details.paymentstatus=2 then "status-complete" end as statusclass')
        ->orderBy('transaction_details.id', 'desc')
        ->get();
        $wdtotal=DB::table('transaction_details')->where([['txntype',1],['paymentstatus',2],['transaction_details.comments','usdt']])
        ->whereBetween('transaction_details.created_at',[$fromDate,$toDate])
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        ->selectRaw('SUM(amountusdt) as amountusdt')
        ->first();
        }
        //dd($wdreport,$wdtotal);
        
        return view('control.withdrawhistorymw')->with('requests',$wdreport)->with('sumtotal',$wdtotal);
    }
    
    public function getReadyToRelease(){
        set_time_limit(0);
            $members=\App\User::where([['users.licence','1'],['user_details.userstatus','1']])
            ->join('user_details','users.id','=','user_details.userid')
            ->join('users as gd','gd.id','=','user_details.sponsorid')
            ->select('users.id as id', 'users.usersname as name', 'users.uuid as userid', 'users.email as email', 'users.doj as doj', 'user_details.current_self_investment as current', 'gd.uuid as guiderid', 'user_details.active_direct as activedirect')
            ->selectRaw('case when user_details.userstatus=0 then "Unpaid" when user_details.userstatus=1 then "Paid" end as status')
            ->selectRaw('case when user_details.userstatus=0 then "status-cancelled" when user_details.userstatus=1 then "status-complete" end as statusclass')
            ->orderByRaw('users.created_at DESC')
            ->get();
        $price=\App\ProfileStore::where('id',1)->first()->price;

        return view('control.readytorelease')->with('members',$members)->with('price',$price);
    }

    public function userPendingWithdrawOTP(){
        $adminwithreq=DB::table('transaction_details')->where([['transaction_details.txntype',1],['b_status','!=',3],['transaction_details.paymentstatus','<',2]])
        ->join('user_details','user_details.id','=','transaction_details.userid')
        ->join('users','users.id','=','user_details.userid')
        ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
        //->join('asset_details','user_details.id','=','asset_details.userid')
        ->select('transaction_details.id as txnid','users.usersname as usersname','users.uuid as uuid','users.email as email','transaction_details.amountsftc as amountsftc','transaction_details.amountusdt as amountusdt', 'transaction_details.net_amount as net_amount', 'transaction_details.deduction as deduction', 'transaction_infos.transaction_hash as hash', 'payment_addr as address', 'currency as currency', 'transaction_details.created_at as txndate')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "Pending" when transaction_details.paymentstatus=1 then "Pending" end as status')
        ->selectRaw('case when transaction_details.paymentstatus=0 then "status-pending" when transaction_details.paymentstatus=1 then "status-pending" end as statusclass')
        ->orderBy('transaction_details.id', 'desc')
        ->get();
        $sumtotal=DB::table('transaction_details')->where([['transaction_details.txntype',1],['b_status','!=',3],['transaction_details.paymentstatus','<',2]])
        ->selectRaw('SUM(amountusdt) as amountusdt')
        ->first();
        return view('control.pendingwithdrawotp')->with('requests',$adminwithreq)->with('sumtotal',$sumtotal);
    }



    public function searchUserforTeamWithdrawalRequest(){
        return view('control.withdrawrequestteamwise')->with('data',array());
    }
    public function searchUserbyTeamWithdrawalRequest(Request $request)
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
            return view('control.withdrawrequestteamwise')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 2.5: Filter only those with stacking_deposites staketype = 4
        $team_internal_ids = DB::table('user_details')
            ->whereIn('user_details.userid', $team_user_ids)          // users.id list
            /*->join('stacking_deposites', function($join){
                $join->on('stacking_deposites.userid', '=', 'user_details.id')
                     ->where('stacking_deposites.staketype', 4);
            })*/
            ->pluck('user_details.id')                   // internal IDs
            ->toArray();

        if(empty($team_internal_ids)){
            return view('control.withdrawrequestteamwise')
                ->with('data', [])
                ->with('totalAmount', 0);
        }

        // STEP 3: Fetch transactions for filtered users
        $data = DB::table('transaction_details')
            ->join('user_details', 'user_details.id', '=', 'transaction_details.userid')
            ->join('users', 'users.id', '=', 'user_details.userid')
            ->join('users as gu', 'gu.id', '=', 'user_details.sponsorid')
            ->join('transaction_infos','transaction_infos.txnid','=','transaction_details.id')
            ->whereIn('transaction_details.userid', $team_internal_ids)
            ->where('transaction_details.txntype', 1)
            ->where('transaction_details.paymentstatus', 1)
            ->where('transaction_details.b_status', 3)
            ->whereBetween('transaction_details.created_at', [$fromDate, $toDate])
            ->select(
                'transaction_details.id as txnid',
                'users.usersname as usersname',
                'users.email',
                'users.uuid as uuid',
                'transaction_details.amountsftc',
                'transaction_details.amountusdt',
                'transaction_details.net_amount',
                'transaction_details.deduction',
                'transaction_details.currency',
                'payment_addr as address',
                'payee_addr as withdrawtype',
                'transaction_details.created_at as txndate'
            )
            ->selectRaw('"Pending" as status')
            ->selectRaw('"status-pending" as statusclass')
            ->get();

        $total = $data->sum('amountusdt');

        return view('control.withdrawrequestteamwise')
            ->with('data', $data)
            ->with('totalAmount', $total);
    }


    

}
