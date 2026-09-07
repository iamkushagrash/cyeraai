<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\DashboardPopup;
use Illuminate\Http\Request;
use App\UserDetails;
use App\TransactionDetail;
use App\AccountDeposit;
use App\TransactionInfo;
use App\WalletTransfer;

class DashboardPopupController extends BaseController
{
    // Get latest popup (only if show_popup = true)
    public function show()
    {
        $popup = DashboardPopup::where('show_popup', true)->latest()->first();

        return response()->json([
            'status' => (bool) $popup,
            'data'   => $popup
        ]);
    }



    //Metawallet to Cyera AI Wallet Add
    public function addWalletFromMetawallet(Request $request){
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
            /*'amountusdt'     =>   ['required','numeric'],*/   
            'txn_pin'     =>   ['required','numeric'],        
        ]);
        if($validator->fails()){
            return $this->sendErrortoMetaWallet('Validation Error.', $validator->errors());
        }
        if($data['Token']=='meta6645dgbbg366sf3566d863'){
            $request->amountusdt=$data['amount'];
            $request->txn_pin=$data['txn_pin'];

            $user=\App\User::where('uuid',$data['user_id'])
            ->join('user_details','users.id','=','user_details.userid')
            ->select('user_details.id as uid','uuid as userid','usersname as name','email as email')->first();

            $userDetail=\App\UserDetails::where('id',$user->uid)->first();
            $profile=\App\ProfileStore::where('id',1)->first();
            $request->amount=$request->amountusdt/$profile->price;

            if($userDetail->user()->permission==0){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Account Blocked']);
            }
            if($request->amountusdt < 10){
                return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Amount should be greater than 10$']);
            }
            if($request->amountusdt>=10){
                \DB::beginTransaction();
                try{
                    $insertWalletRequest=\App\TransactionDetail::insertGetId([
                        "userid"  => $userDetail->id,
                        "txntype"  => 0,
                        "amountsftc"  => ($request->amount),
                        "amountusdt"  => $request->amountusdt,
                        "remaining"  => 0, 
                        "paymentstatus"  => 2,
                        "txndesc"  => "User Deposit",
                        "comments"  => 'wallet',
                        "planid"  => 0,
                        "currency"  => 'usdt',
                        "created_at"  => now(),
                        "release_date"  =>date('Y-m-d'),
                    ]);
                    $insTxnInfo=\App\TransactionInfo::create([
                        "txnid"  =>  $insertWalletRequest,
                        "payment_addr"  =>  'CAI Deposit',
                        "transaction_hash"  =>  'MetaWallet',
                        "contract_addr" =>' ',
                        "amount"  =>  $request->amountusdt,
                        "txn_status"  =>  2,
                    ]);
                    $promotionalAdd=\App\AccountDeposit::firstOrNew([
                        'userid'    =>  $userDetail->id,
                    ]);
                    if(!is_null($promotionalAdd->amount)){
                      $amt=Crypt::decrypt($promotionalAdd->amount)+($request->amountusdt);
                    }else{
                      $amt=($request->amountusdt);
                    }
                    $promotionalAdd->amount=(Crypt::encrypt($amt));
                    $promotionalAdd->save();
                    $walletTransferEntry=\App\WalletTransfer::insertGetId([
                      'userid'  =>  $userDetail->id,
                      'txnid'  =>  $insertWalletRequest,
                      'fromWallet'  =>  'deposite',
                      'toWallet'  =>  'wallet',
                      'amount'  =>  ($request->amountusdt),
                      'release_date'    => date('Y-m-d'),
                       "created_at"  => now(),
                    ]);
                }
                catch(Exception $e){
                    \DB::rollback();
                    \Log::info('Error for User '.$userDetail->id. ' Error message is '.$e->getMessage());
                    return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'You have some issue with wallet add. Please Try again.']);
                }
                \DB::commit();
                $details['success']=true;
                $details['msg']='Wallet Added Successfully';
                $details['Status']=2;
                $details['txn_pin']=$request->txn_pin;
                $details['amount']=$request->amountusdt;
                return $this->sendResponsetoMetaWallet($details,'Wallet Added Successfully');
            }

        }else{
            return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Token Mismatch']);
        }

    }


    //Check remaining Income and Available Wallet
    public function checkIncomeAndWalletforMetaW(Request $request){
        $allowedIps = ['127.0.0.1','122.176.71.56','88.198.20.15'];

        $data=$request->all();
        $clientIp = $request->ip();
        /*dd($clientIp);*/
        if (!in_array($clientIp, $allowedIps)) {
            return $this->sendErrortoMetaWallet('Access Denied', ['errors' => 'IP Not Whitelisted']);
        }

        if(!isset($data['Wallet']) || is_null($data['Wallet'])){
             return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Select proper wallet']);
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
        if ($data['Wallet']=='income') {
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
        elseif($data['Wallet']=='Topup'){
            if($data['Token']=='meta6645dgbbg366sf3566d863'){
                $user=\App\User::where('uuid',$data['user_id'])
                ->join('user_details','users.id','=','user_details.userid')
                ->select('user_details.id as uid','uuid as userid','usersname as name','email as email')->first();

                $userDetail=\App\UserDetails::where('id',$user->uid)->first();
                if(!is_null(\App\AccountDeposit::where('userid',$userDetail->id)->first())){
                    $amt=Crypt::decrypt(\App\AccountDeposit::where('userid',$userDetail->id)->first()->amount);
                }else{
                    $amt=0;
                }
                $details['success']=true;
                //$details['mwtPrice']=$price=\App\ProfileStore::where('id',1)->first()->price;
                $details['balance']=$amt;
                //$details['balance_usdt']=($userDetail->remainingIncome()*$price);
                

                return $this->sendResponsetoMetaWallet($details,'MetaWallet Balance');

            }else{
                return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Token Mismatch']);
            }
        }
        else{
            return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Use correct wallet type']);
        }
        



    }



    //Credit and Debit from Metawallet
    public function walletCreditWithdrawDebitBoth(Request $request){
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
            'Wallet'     =>   ['required','string'],
            'txn_type'     =>   ['required','string'],
            'amount'     =>   ['required','numeric'],  
            'TID'     =>   ['required','numeric'],       
        ]);
        if($validator->fails()){
            return $this->sendErrortoMetaWallet('Validation Error.', $validator->errors());
        }

        if ($data['Wallet']=='Topup' && $data['txn_type']=='C') {
            if($data['Token']=='meta6645dgbbg366sf3566d863'){
                $request->amountusdt=$data['amount'];
                $request->txn_pin=$data['TID'];

                $user=\App\User::where('uuid',$data['user_id'])
                ->join('user_details','users.id','=','user_details.userid')
                ->select('user_details.id as uid','uuid as userid','usersname as name','email as email')->first();

                $userDetail=\App\UserDetails::where('id',$user->uid)->first();
                $profile=\App\ProfileStore::where('id',1)->first();
                $request->amount=$request->amountusdt/$profile->price;

                if($userDetail->user()->permission==0){
                    return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Account Blocked']);
                }
                if($request->amountusdt < 1){
                    return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'Amount should be greater than 1$']);
                }
                if($request->amountusdt>=1){
                    \DB::beginTransaction();
                    try{
                        $insertWalletRequest=\App\TransactionDetail::insertGetId([
                            "userid"  => $userDetail->id,
                            "txntype"  => 0,
                            "amountsftc"  => ($request->amount),
                            "amountusdt"  => $request->amountusdt,
                            "remaining"  => 0, 
                            "paymentstatus"  => 2,
                            "txndesc"  => "User Deposit",
                            "comments"  => 'wallet',
                            "planid"  => 0,
                            "currency"  => 'usdt',
                            "created_at"  => now(),
                            "release_date"  =>date('Y-m-d'),
                        ]);
                        $insTxnInfo=\App\TransactionInfo::create([
                            "txnid"  =>  $insertWalletRequest,
                            "payment_addr"  =>  'CAI Deposit',
                            "transaction_hash"  =>  'MetaWallet',
                            "contract_addr" =>' ',
                            "amount"  =>  $request->amountusdt,
                            "txn_status"  =>  2,
                        ]);
                        $promotionalAdd=\App\AccountDeposit::firstOrNew([
                            'userid'    =>  $userDetail->id,
                        ]);
                        if(!is_null($promotionalAdd->amount)){
                          $amt=Crypt::decrypt($promotionalAdd->amount)+($request->amountusdt);
                        }else{
                          $amt=($request->amountusdt);
                        }
                        $promotionalAdd->amount=(Crypt::encrypt($amt));
                        $promotionalAdd->save();
                        $walletTransferEntry=\App\WalletTransfer::insertGetId([
                          'userid'  =>  $userDetail->id,
                          'txnid'  =>  $insertWalletRequest,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'wallet',
                          'amount'  =>  ($request->amountusdt),
                          'release_date'    => date('Y-m-d'),
                           "created_at"  => now(),
                        ]);
                    }
                    catch(Exception $e){
                        \DB::rollback();
                        \Log::info('Error for User '.$userDetail->id. ' Error message is '.$e->getMessage());
                        return $this->sendErrortoMetaWallet('Validation Error.', ["error"=>'You have some issue with wallet add. Please Try again.']);
                    }
                    \DB::commit();
                    $details['success']=true;
                    $details['msg']='Wallet Added Successfully';
                    $details['Status']=2;
                    $details['TID']=$request->txn_pin;
                    $details['amount']=$request->amountusdt;
                    return $this->sendResponsetoMetaWallet($details,'Wallet Added Successfully');
                }

            }else{
                return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Token Mismatch']);
            }
        }
        elseif($data['Wallet']=='income' && $data['txn_type']=='D'){
            if($data['Token']=='meta6645dgbbg366sf3566d863'){
                $request->amount_usdt=$data['amount'];
                $request->txn_pin=$data['TID'];

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
                    $details['TID']=$request->txn_pin;
                    $details['amount']=($request->amount_usdt*.90);

                    return $this->sendResponsetoMetaWallet($details,'Withdraw Successfully');

                }
                else{
                    return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Withdrawal amount greater than balance']);
                }
                
            }else{
                return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Token Mismatch']);
            }
        }
        else{
            return $this->sendErrortoMetaWallet('Validation Error', ['errors'   => 'Use correct wallet or transaction type']);
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
