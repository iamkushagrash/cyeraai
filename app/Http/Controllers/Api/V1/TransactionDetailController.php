<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use \App\Http\Controllers\NPController;
use \App\Http\Controllers\SupportQueryController;
use App\UserDetails;
use App\TransactionDetail;
use DB;

class TransactionDetailController extends BaseController
{
    public function getPGTokenType(Request $request){
        $userExistingPayment=\App\TransactionDetail::where([['userid',$request->user()->userDetails()->first()->id],['paymentstatus','<',2],['txntype',0],['release_date','>',date('Y-m-d H:i:s',strtotime('- 5 minutes',strtotime(now())))]])->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
            ->select('comments as payment_id','transaction_infos.payment_addr as pay_address')->first();
        if(!is_null($userExistingPayment))
            return $this->sendResponse($this->existingTxnInfo($userExistingPayment->payment_id), 'Pending Transaction');
        $detail['currency']=['usdtbsc'];
        $detail['paymentstatus']=0;
        return $this->sendResponse($detail, 'Deposit Currency');
    }

    public function depositPGCurrency(Request $request){
        if(isset($request->txnid)){
           return $this->existingTxnInfo($request->txnid);
        }else{
            $userExistingPayment=\App\TransactionDetail::where([['userid',$request->user()->userDetails()->first()->id],['paymentstatus','<',2],['txntype',0],['release_date','>',date('Y-m-d H:i:s',strtotime('- 5 minutes',strtotime(now())))]])->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
            ->select('comments as payment_id','transaction_infos.payment_addr as pay_address')->first();
            if(is_null($userExistingPayment)){
                $vali=Validator::make($request->all(), [
                    'amountusdt' => ['required', 'numeric','min:1'],
                    'currency' => ['required', 'string' ],
                ]);
                if($vali->fails()){
                    return $this->sendError('Validation Error.', $vali->errors());
                }
                return $this->buyRequestPGMobile($request);
            }else{
                return $this->existingTxnInfo($userExistingPayment->payment_id);
            }
        }
    }

    public function buyRequestPGMobile($request){//dd($request->amountusdt);
        $arrayParm = array("price_amount"=> $request->amountusdt,
          "price_currency"=> "usd",
          "pay_currency"=> "usdtbsc",
          "ipn_callback_url"=> "https://nowpayments.io",
          "is_fixed_rate"=> true,
          "is_fee_paid_by_user"=> false,
        );
        $npObject=new NPController();

        $paymentCreation=json_decode($npObject->createPayment($arrayParm));
        if(isset($paymentCreation->payment_status) && $paymentCreation->payment_status!='failed')
        {
            $insertTransactionDetails=\App\TransactionDetail::insertGetId([
                'userid'  =>  $request->user()->userDetails()->first()->id,
                'txntype'  =>  0,
                'amountsftc'  =>  0,
                'amountusdt'  =>  $request->amountusdt,
                'remaining'  =>  $request->amountusdt,
                'paymentstatus'  =>  0,
                'txndesc'  =>  'User Deposit',
                'comments'  =>  $paymentCreation->payment_id,
                'currency'  =>  $paymentCreation->pay_currency,
                'release_date'  =>  date('Y-m-d H:i:s',strtotime('+ 19 minutes',strtotime(now()))),
                'created_at'  =>  now(),
                'updated_at'  =>  now(),
            ]);
            $insertTransactionInfo=\App\TransactionInfo::create([
                'txnid'  =>  $insertTransactionDetails,
                'payment_addr'  =>  $paymentCreation->pay_address,
                'payee_addr'  =>  '',
                'transaction_hash'  =>  '',
                'amount'  =>  0,
                'contract_addr'  =>  $paymentCreation->purchase_id,
                'txn_status'  =>  0,
            ]);
            $detail['paymentstatus']=1;
            $detail['address']=$paymentCreation->pay_address;
            $detail['amount']=$request->amountusdt;
            $detail['url']='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$paymentCreation->pay_address;
            $detail['txnid']=$paymentCreation->payment_id;
            \Log::info(json_encode($paymentCreation));
            //$transactionDetail=\App\TransactionDetail::where('transaction_details.id',$insertTransactionDetails)
            //->join('transaction_infos','transaction_details.id','=','transaction_infos.txnid')
            //->select('comments as payment_id','transaction_infos.payment_addr as pay_address')->first();
            return $this->sendResponse($detail, 'Payment request generated successfully.');
        }else{
            \Log::info('Error in deposit of user '.$request->user()->userDetails()->first()->id);
            \Log::info('Error is');
            \Log::info(json_encode($paymentCreation));
            $detail['currency']=['usdtbsc'];
            return $this->sendError('Oops Some thing went wrong. Please try again after sometime.', $detail);
        }  
    }

    public function existingTxnInfo($id)
    {
        $paymentStatus = 0;

        // Fetch transaction (0,1,2 allowed)
        $transaction = \App\TransactionDetail::where([
            ['comments', $id],
            ['txntype', 0],
            ['paymentstatus', '<', 3]   // ✔ allow 0,1,2
        ])->first();

        // If no transaction found
        if (!$transaction) {
            $detail['paymentstatus'] = 3;
            $detail['currency'] = ['usdtbsc'];
            return $this->sendError('Please Check Transaction History', $detail);
        }

        $np = new NPController();
        $paymentCreation = json_decode($np->getPaymentStatus($id));

        // -------------------------
        // Start atomic processing
        // -------------------------
        DB::transaction(function () use (&$transaction, $paymentCreation, &$response) {

            // Lock the row first
            $transaction = \App\TransactionDetail::where('id', $transaction->id)
                ->lockForUpdate()
                ->first();

            // Already confirmed
            if ($transaction->paymentstatus == 2) {

                $detail['paymentstatus'] = 2;
                $detail['currency'] = ['usdtbsc'];

                $response = $this->sendResponse($detail, 'Transaction already confirmed.');

                return;
            }

            // FAILED CASE
            if (in_array($paymentCreation->payment_status, ['failed', 'refunded', 'expired'])) {

                \App\TransactionDetail::where('id', $transaction->id)
                    ->update([
                        'paymentstatus' => 3,
                        'updated_at' => now()
                    ]);

                \App\TransactionInfo::where('txnid', $transaction->id)
                    ->update([
                        'txn_status' => 3,
                        'updated_at' => now()
                    ]);

                $detail['paymentstatus'] = 3;
                $detail['currency'] = ['usdtbsc'];

                $response = $this->sendError('Oops! Something went wrong. Transaction failed.', $detail);

                return;
            }

            // SUCCESS CASE
            if (
                in_array($paymentCreation->payment_status, ['finished', 'confirmed', 'sending']) ||
                ($paymentCreation->payment_status == 'partially_paid' &&
                 $transaction->release_date <= now() &&
                 $transaction->remaining <= $paymentCreation->actually_paid)
            ) {

                // Update transaction
                \App\TransactionDetail::where('id', $transaction->id)
                    ->update([
                        'paymentstatus' => 2,
                        'remaining' => 0,
                        'amountusdt' => $paymentCreation->actually_paid,
                        'updated_at' => now()
                    ]);

                \App\TransactionInfo::where('txnid', $transaction->id)
                    ->update([
                        'txn_status' => 2,
                        'amount' => $paymentCreation->actually_paid,
                        'transaction_hash' => $paymentCreation->payin_hash,
                        'updated_at' => now()
                    ]);

                // Wallet credit
                \App\WalletTransfer::create([
                    'userid' => $transaction->userid,
                    'txnid' => $transaction->id,
                    'fromWallet' => 'deposite',
                    'toWallet' => 'wallet',
                    'amount' => $paymentCreation->actually_paid,
                    'fromUser' => $transaction->userid,
                    'release_date' => date('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Promotion
                $promo = \App\AccountDeposit::firstOrNew(['userid' => $transaction->userid]);
                $amt = !is_null($promo->amount)
                    ? Crypt::decrypt($promo->amount) + $paymentCreation->actually_paid
                    : $paymentCreation->actually_paid;

                $promo->amount = Crypt::encrypt($amt);
                $promo->save();

                // Email send
                try {
                    $sendMail = new SupportQueryController();
                    $userdt = \App\UserDetails::where('id', $transaction->userid)->first();

                    $details = [
                        'email' => $userdt->user()->email,
                        'subject' => 'Your deposit confirmed at Cyera AI',
                        'view' => 'depositmail',
                        'amount' => $paymentCreation->actually_paid,
                        'userid' => $userdt->user()->uuid
                    ];
                    $sendMail->sendMailgun($details);

                } catch (\Exception $e) {
                    \Log::info("Mail error user {$transaction->userid}");
                }

                $detail['paymentstatus'] = 2;
                $detail['currency'] = ['usdtbsc'];

                $response = $this->sendResponse($detail, 'Transaction confirmed successfully.');

                return;
            }

            // WAITING STATUS
            if ($paymentCreation->payment_status == 'waiting') {

                $detail['paymentstatus'] = 1;
                $detail['address'] = $paymentCreation->pay_address;
                $detail['url'] = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$paymentCreation->pay_address;
                $detail['paid'] = $paymentCreation->actually_paid;
                $detail['currency'] = ['usdtbsc'];
                $detail['txnid'] = $paymentCreation->payment_id;

                $response = $this->sendResponse($detail, 'We are waiting for transaction.');

                return;
            }
        });

        return $response;
    }


    public function existingTxnInfoOld($id){
        $paymentStatus=0;//0=unpaid 1=paid 2=filed or returned
        $transaction=\App\TransactionDetail::where([['paymentstatus','<',2],['comments',$id],['txntype',0]])->first();
        if(!is_null($transaction)){//\Log::info(!is_null($transaction));
            $npObject=new NPController();
            $paymentCreation=json_decode($npObject->getPaymentStatus($id));//dd($paymentCreation->payment_status);
            if($paymentCreation->payment_status=='failed' || $paymentCreation->payment_status=='refunded' || $paymentCreation->payment_status=='expired') {
                $transactionDetailUpate=\App\TransactionDetail::where('id',$transaction->id)->update([
                    'paymentstatus'  =>   3,
                    'updated_at'    =>  now(),
                ]);
                $transactionInfoUpdate=\App\TransactionInfo::where('txnid',$transaction->id)->update([
                    'txn_status'  =>  3,
                    'updated_at'    =>  now(),
                ]);
                $detail['paymentstatus']=3;
                $detail['currency']=['usdtbsc'];
                return $this->sendError('Oops Some thing went wrong. Please try again after sometime.', $detail);
            }elseif($paymentCreation->payment_status=='finished' || $paymentCreation->payment_status=='confirmed' || $paymentCreation->payment_status=='sending'|| ($paymentCreation->payment_status=='partially_paid' || $transaction->release_date<=date('Y-m-d H:i:s') && $transaction->remaining<=$paymentCreation->actually_paid)){
                $transactionDetailUpate=\App\TransactionDetail::where('id',$transaction->id)->update([
                    'paymentstatus'  =>   2,
                    'remaining' =>  0,
                    'amountusdt'    => $paymentCreation->actually_paid,
                    'updated_at'    =>  now(),
                ]);
                $transactionInfoUpdate=\App\TransactionInfo::where('txnid',$transaction->id)->update([
                    'txn_status'  =>  2,
                    'amount'    =>  $paymentCreation->actually_paid,
                    'transaction_hash'  =>  $paymentCreation->payin_hash,
                    'updated_at'    =>  now(),
                ]);
                $userWalletUpdate=\App\WalletTransfer::create([
                    'userid'  =>  $transaction->userid,
                    'txnid'  =>  $transaction->id,
                    'fromWallet'  =>  'deposite',
                    'toWallet'  =>  'wallet',
                    'amount'  =>  $paymentCreation->actually_paid,
                    'fromUser'  =>  $transaction->userid,
                    'release_date'  =>  date('Y-m-d'),
                    'created_at'  =>  now(),
                    'updated_at'  =>  now(),
                ]);
                $promotionalAdd=\App\AccountDeposit::firstOrNew([
                      'userid'    =>  $transaction->userid,
                  ]);
                if(!is_null($promotionalAdd->amount)){
                  $amt=Crypt::decrypt($promotionalAdd->amount)+($paymentCreation->actually_paid);
                }else{
                  $amt=($paymentCreation->actually_paid);
                }
                $promotionalAdd->amount=(Crypt::encrypt($amt));
                $promotionalAdd->save();

                $userdt=\App\UserDetails::where('id',$transaction->userid)->first();
               // \Log::info($transaction);\Log::info($userdt);\Log::info($userdt->user());
                try{
                    $sendMail=new SupportQueryController();
                    $details['email']=$userdt->user()->email;
                    $details['subject']='Your deposit confirmed at Cyera AI';
                    $details['view']='depositmail';
                    $details['amount']=$paymentCreation->actually_paid;
                    $details['userid']=$userdt->user()->uuid;
                    $status=$sendMail->sendMailgun($details);
                }catch(Exception $e){
                    \Log::info('Error in sending Topupmail for userid '.$transaction->userid);
                    \Log::info($e->messages());
                }
                $detail['paymentstatus']=2;
                $detail['currency']=['usdtbsc'];
                $transactionMessage='Transaction confirmed successfully. Amount transferred to your wallet.';
                return $this->sendResponse($detail, $transactionMessage);
            }elseif($paymentCreation->payment_status=='waiting'){
                $detail['paymentstatus']=1;
                $detail['address']=$paymentCreation->pay_address;
                $detail['url']='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$paymentCreation->pay_address;
                $detail['paid']=$paymentCreation->actually_paid;
                $detail['currency']=['usdtbsc'];
                $detail['txnid']=$paymentCreation->payment_id;//dd($paymentCreation,$id);
                return $this->sendResponse($detail, 'We are waiting for transaction.');
            }
            \Log::info('payment status of id '.$id);\Log::info(json_encode($paymentCreation));
            \Log::info(json_encode(array('status'   =>  1,'payment_status'=>$paymentCreation->payment_status,'paid' =>$paymentCreation->actually_paid,'totalAmount' =>  $paymentCreation->pay_amount)));
        }else{\Log::info(is_null($transaction));
            $detail['paymentstatus']=3;
            $detail['currency']=['usdtbsc'];
            return $this->sendError($detail,'Please Check Transaction History');
        }
    }


}
