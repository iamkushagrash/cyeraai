<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class TransactionInfoController extends Controller
{
    //


    public function sftcTransactioncheck(){
        $status="";
        $users=\App\TransactionDetail::where([['txntype',0],['paymentstatus',1],['ti.txn_status',1],['comments','wallet']])
        ->join('transaction_infos as ti','transaction_details.id','=','ti.txnid')
        ->select('transaction_details.userid as userid','transaction_details.amountsftc as amount','transaction_details.amountusdt as amountusdt','transaction_details.remaining as remaining','ti.payment_addr as toaddress','ti.transaction_hash as txnhash','transaction_details.id as id','transaction_details.currency','ti.contract_addr','comments','planid')
        ->get();
        $upd='';
        foreach ($users as $user) {//\Log::info($user->currency);
            if($user->currency=='usdt')
                $arr=$this->trc20TokenTransaction($user);
            elseif($user->currency=='usdtbep20')
                $arr=$this->bep20TokenTransaction($user);
            /*if(!sizeof($data)){
              $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0,'transaction_hash'=>'']);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
              continue;
            }
            $fromAddr=$data['ownerAddress'];*/
            /*if($data['contractType']==1){
                $toAddr=$data['toAddress'];
                $noOfToken=($data['contractData']['amount']/1000000);
                $contract='';
            }
            else*//* if($data['contractType']==31){
                $contract=$data['toAddress'];
                $noOfToken=($data['tokenTransferInfo']['amount_str']/1000000);
                $toAddr=$data['tokenTransferInfo']['to_address'];
            }
            else{
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0,'transaction_hash'=>'']);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
              continue;
            }
            if($data['contractRet']=='SUCCESS')
                {
                    $status=3;
                }else
                {
                    $status=4;
                }
            if($contract!=env($user->currency.'_contract_addr')){
              $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0]);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
              break;
            }
            $amount=0;
            $remaining=0;
            if($noOfToken>$user->remaining || $noOfToken==($user->remaining)){
                $amount=$noOfToken;
            }elseif($noOfToken<($user->remaining)){
              $amount=$noOfToken;
              $remaining=$user->remaining-$noOfToken;
            }
            $releaseDate=date("Y-m-d",strtotime(date("Y-m-d")."+ 104 weeks"));
            if($status==3){
              $toa=strcasecmp($user->toaddress,$toAddr);
              if($toa==0){
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                if($remaining==0){
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['release_date'=>$releaseDate,'paymentstatus'=>2,'remaining'=>0]);
                  $udupdate=\App\UserDetails::where('id',$user->userid)->update([
                    'userstatus'=>1,
                    'capping'=>0,
                  ]);
                  if($user->comments=='basic'){
                      $udupdate=\App\UserDetails::where('id',$user->userid);
                      $udupdate->increment('userstate');
                      $udupdate->increment('total_investment',$user->amount);
                      $udupdate->increment('current_investment',$user->amount);
                      $udupdate->increment('total_self_investment',$user->amount);
                      $udupdate->increment('current_self_investment',$user->amount);
                      $basicWalletUpdate=\App\BasicWallet::firstOrCreate(['userid'=>$user->userid]);
                      $basicWalletUpdate->increment('amount',$user->amount);
                      $walletTransferEntry=\App\WalletTransfer::insertGetId([
                          'userid'  =>  $user->userid,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'Basic',
                          'amount'  =>  $user->amount,
                      ]);
                      $data['id']=$user->userid;
                      $data['amount']=$user->amount;
                      $data['txnid']=$walletTransferEntry;
                      event(new \App\Events\BasicDeposite($data));
                  }elseif($user->comments=='Funding'){
                      $promotionalAdd=\App\PromotionalWallet::firstOrCreate(['userid'=>$user->userid]);
                      $promotionalAdd->increment('amount',$user->amount);
                      $walletTransferEntry=\App\WalletTransfer::create([
                          'userid'  =>  $user->userid,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'Funding',
                          'amount'  =>  $user->amount,
                      ]);
                  }
                }elseif($remaining>0){
                    $updi=\App\TransactionDetail::where('id',$user->id)->update(['remaining'=>$remaining,'paymentstatus'=> 0]);
                    $insexter=\App\TransactionInfo::create([
                                'txnid' => $user->id,
                                'payment_addr'  => $user->toaddress,
                                'transaction_hash'   => '',
                                'txn_status'  => 0,
                                'contract_addr'  =>  env($user->currency.'_contract_addr'),
                            ]);
                    $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                }
              }
              else{
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>3,'amount'=>0]);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3,]);
              }
            }*/
        }
    }

    public function bep20TokenTransaction($user){

        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.bscscan.com/api?module=proxy&action=eth_getTransactionByHash&txhash=".str_replace(" ","",$user->txnhash)."&apikey=WTAWNWW4WHR577JZMHSDIJRYZV1WWB7TX4",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            $data=json_decode($response,true);\Log::info($data);
            if(!sizeof($data) || is_null($data['result'])){
              $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>3,'amount'=>0,'transaction_hash'=>'']);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3]);\Log::info('data not found');
              //continue;
            }
            else{
                if($data['result']['chainId']==='0x38'){
                  $fromAddr=$data["result"]["from"];
                  $contract=$data["result"]["to"];
                  $noOfToken=(hexdec(substr($data["result"]["input"],(strlen($data["result"]["input"])-64)))/pow(10,18));
                  $toAddr="0x".(substr($data["result"]["input"],(strlen($data["result"]["input"])-104),(strlen($data["result"]["input"])-98)));
                  $toAddr1=substr($toAddr,(strlen($toAddr)-104),(strlen($toAddr)-88));

                  \Log::info('amount '.$noOfToken);
                  if(strcasecmp($contract,env($user->currency.'_contract_addr'))){
                    $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0]);
                    $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
                    \Log::info('contract address not matched incoming -> '.$contract.' present -> '.env($user->currency.'_contract_addr'));
                    //break;
                  }else{
                    $amount=$noOfToken;
                    $remaining=0;
                    $toa=strcasecmp($user->toaddress,$toAddr);
                    \Log::info($toa.' '.$toAddr.' '.$user->toaddress);
                    if($toa==0){
                      $priceUpd=\App\ProfileStore::all()->first();
                      $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                      if($remaining==0){
                        $updi=\App\TransactionDetail::where('id',$user->id)->update(['release_date'=>date('Y-m-d'),'paymentstatus'=>2,'remaining'=>0,'amountsftc'=>($amount/$priceUpd->price),'amountusdt'=>$amount]);
                        if($user->comments=='basic'){
                          $udupdate=\App\UserDetails::where('id',$user->userid)->update([
                            'userstatus'=>1,
                            'capping'=>0,
                          ]);
                          $udupdate=\App\UserDetails::where('id',$user->userid);
                          $udupdate->increment('userstate');
                          $udupdate->increment('total_investment',$amount);
                          $udupdate->increment('current_investment',$amount);
                          $udupdate->increment('total_self_investment',$amount);
                          $udupdate->increment('current_self_investment',$amount);
                          $basicWalletUpdate=\App\StackingDeposite::create([
                                'userid'    =>  $user->userid,
                                'planid'    =>  $user->planid,
                                'amount'    =>  $amount,
                            ]);
                          $plan=\App\StackingDetail::where('id',$user->planid)->first();
                          $walletTransferEntry=\App\WalletTransfer::insertGetId([
                              'txnid'  =>  $user->id,
                              'fromWallet'  =>  'deposite',
                              'toWallet'  =>  'basic',
                              'amount'  =>  $amount,
                              'release_date'    => date('Y-m-d',strtotime('+ '.$plan->duration.' months',strtotime(now()))),
                          ]);
                          /*$data['id']=$user->userid;
                          $data['amount']=$user->amount;
                          $data['txnid']=$walletTransferEntry;*/
                          //event(new \App\Events\BasicDeposite($data));
                        }elseif($user->comments=='fixed'){
                          $promotionalAdd=\App\FixedStackingDeposite::create([
                                'userid'    =>  $user->userid,
                                'planid'    =>  $user->planid,
                                'amount'    =>  $amount,
                            ]);
                          $promotionalAdd->increment('amount',$amount);
                          $plan=\App\FixedStackingDetail::where('id',$user->planid)->first();
                          $walletTransferEntry=\App\WalletTransfer::insertGetId([
                            'txnid'  =>  $user->id,
                            'fromWallet'  =>  'deposite',
                            'toWallet'  =>  'fixed',
                            'amount'  =>  $amount,
                            'release_date'    => date('Y-m-d',strtotime('+ '.$plan->duration.' months',strtotime(now()))),
                          ]);
                        }elseif($user->comments=='wallet'){
                          $promotionalAdd=\App\AccountDeposit::firstOrNew([
                                'userid'    =>  $user->userid,
                            ]);
                          if(!is_null($promotionalAdd->amount)){
                            $amt=Crypt::decrypt($promotionalAdd->amount)+($amount);
                          }else{
                            $amt=($amount);
                          }
                          $promotionalAdd->amount=(Crypt::encrypt($amt));
                          $promotionalAdd->save();
                          $walletTransferEntry=\App\WalletTransfer::insertGetId([
                            'userid'  =>  $user->userid,
                            'txnid'  =>  $user->id,
                            'fromWallet'  =>  'deposite',
                            'toWallet'  =>  'wallet',
                            'amount'  =>  ($amount),
                            'release_date'    => date('Y-m-d'),
                          ]);

                          //25 % extra 
                          /*if($amount>100){
                            $walletTransferEntry=\App\WalletTransfer::insertGetId([
                              'userid'  =>  $user->userid,
                              'txnid'  =>  $user->id,
                              'fromWallet'  =>  'Bonus 25',
                              'toWallet'  =>  'nWallet',
                              'amount'  =>  (1/$priceUpd->price*$amount*0.25),
                              'release_date'    => date('Y-m-d'),
                              'fromUser'  =>  1,
                            ]);
                            $networkWalletEntry=\App\NetworkingWallet::firstOrNew([
                                  'userid'    =>  $user->userid,
                              ]);
                            if(!is_null($networkWalletEntry->amount)){
                              $amt=Crypt::decrypt($networkWalletEntry->amount)+(1/$priceUpd->price*$amount*0.25);
                            }else{
                              $amt=(1/$priceUpd->price*$amount*0.25);
                            }
                            $networkWalletEntry->amount=(Crypt::encrypt($amt));
                            $networkWalletEntry->save();
                          }*/


                        }
                      }//New Txngeneration in remaining
                      /*elseif($remaining>0){
                          $updi=\App\TransactionDetail::where('id',$user->id)->update(['remaining'=>$remaining,'paymentstatus'=> 0]);
                          $insexter=\App\TransactionInfo::create([
                                      'txnid' => $user->id,
                                      'payment_addr'  => $user->toaddress,
                                      'transaction_hash'   => '',
                                      'txn_status'  => 0,
                                      'contract_addr'  =>  env($user->currency.'_contract_addr'),
                                  ]);
                          $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                      }*/
                    }
                    else{
                      $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payment_addr'=>$toAddr,'payee_addr'=>$fromAddr,'txn_status'=>3,'amount'=>0]);
                      $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3,]);
                    }
                  }
              }
              else{
                  $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0,'transaction_hash'=>'']);
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
                //continue;
              }
            }
            
            
            /*$amount=$noOfToken;
            $remaining=0;
              $toa=strcasecmp($user->toaddress,$toAddr);
              if($toa==0){
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                if($remaining==0){
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['release_date'=>$releaseDate,'paymentstatus'=>2,'remaining'=>0]);

                  $udupdate=\App\UserDetails::where('id',$user->userid)->update([
                    'userstatus'=>1,
                    'capping'=>0,
                  ]);
                  if($user->comments=='basic'){
                      $udupdate=\App\UserDetails::where('id',$user->userid);
                      $udupdate->increment('userstate');
                      $udupdate->increment('total_investment',$user->amount);
                      $udupdate->increment('current_investment',$user->amount);
                      $udupdate->increment('total_self_investment',$user->amount);
                      $udupdate->increment('current_self_investment',$user->amount);
                      $basicWalletUpdate=\App\BasicWallet::firstOrCreate(['userid'=>$user->userid]);
                      $basicWalletUpdate->increment('amount',$user->amount);
                      $walletTransferEntry=\App\WalletTransfer::insertGetId([
                          'userid'  =>  $user->userid,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'Basic',
                          'amount'  =>  $user->amount,
                      ]);
                      $data['id']=$user->userid;
                      $data['amount']=$user->amount;
                      $data['txnid']=$walletTransferEntry;
                      event(new \App\Events\BasicDeposite($data));
                  }elseif($user->comments=='Funding'){
                      $promotionalAdd=\App\PromotionalWallet::firstOrCreate(['userid'=>$user->userid]);
                      $promotionalAdd->increment('amount',$user->amount);
                      $walletTransferEntry=\App\WalletTransfer::create([
                          'userid'  =>  $user->userid,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'Funding',
                          'amount'  =>  $user->amount,
                      ]);
                  }
                }elseif($remaining>0){
                    $updi=\App\TransactionDetail::where('id',$user->id)->update(['remaining'=>$remaining,'paymentstatus'=> 0]);
                    $insexter=\App\TransactionInfo::create([
                                'txnid' => $user->id,
                                'payment_addr'  => $user->toaddress,
                                'transaction_hash'   => '',
                                'txn_status'  => 0,
                                'contract_addr'  =>  env($user->currency.'_contract_addr'),
                            ]);
                    $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                }
              }
              else{
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>3,'amount'=>0]);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3,]);
              }
            */
        curl_close($curl);
    }


    public function trc20TokenTransaction($user){
        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => "https://apilist.tronscan.org/api/transaction-info?hash=".str_replace(" ","",$user->txnhash),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);\Log::info('trx');
            $data=json_decode($response,true);\Log::info($data);
            if(!sizeof($data) || isset($data['message'])){
              $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>3,'amount'=>0,'transaction_hash'=>'']);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3]);
              //continue;
            }
            else{
              $profileStore=\App\ProfileStore::all()->first();
              $fromAddr=$data['ownerAddress'];
              /*$toAddr=$data["result"]["logs"][0]["topics"][2];
              \Log::info(hexdec($data["result"]["logs"][0]["data"]));
              */
              if($data['contractType']==31){
                  $contract=$data['toAddress'];
                  $noOfToken=($data['tokenTransferInfo']['amount_str']/1000000);
                  $toAddr=$data['tokenTransferInfo']['to_address'];
              }
              else{
                  $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0,'transaction_hash'=>'']);
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
                //continue;
              }
              if($data['contractRet']=='SUCCESS')
              {
                  $status=3;
              }else
              {
                  $status=4;
              }
              if($contract!=env($user->currency.'_contract_addr')){
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0]);
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
                //break;
              }
              $amount=$noOfToken;
              $remaining=0;
              if($status==3){
                $toa=strcasecmp($user->toaddress,$toAddr);
                if($toa==0){
                  $priceUpd=$user->amount/$user->amountusdt;
                  $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                  if($remaining==0){
                    $updi=\App\TransactionDetail::where('id',$user->id)->update(['release_date'=>date('Y-m-d'),'paymentstatus'=>2,'remaining'=>0,'amountsftc'=>($priceUpd*$amount),'amountusdt'=>$amount]);
                    if($user->comments=='basic'){
                      $udupdate=\App\UserDetails::where('id',$user->userid)->update([
                        'userstatus'=>1,
                        'capping'=>0,
                      ]);
                      $udupdate=\App\UserDetails::where('id',$user->userid);
                      $udupdate->increment('userstate');
                      $udupdate->increment('total_investment',$amount);
                      $udupdate->increment('current_investment',$amount);
                      $udupdate->increment('total_self_investment',$amount);
                      $udupdate->increment('current_self_investment',$amount);
                      $basicWalletUpdate=\App\StackingDeposite::create([
                            'userid'    =>  $user->userid,
                            'planid'    =>  $user->planid,
                            'amount'    =>  $amount,
                        ]);
                      $plan=\App\StackingDetail::where('id',$user->planid)->first();
                      $walletTransferEntry=\App\WalletTransfer::insertGetId([
                          'txnid'  =>  $user->id,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'basic',
                          'amount'  =>  $amount,
                          'release_date'    => date('Y-m-d',strtotime('+ '.$plan->duration.' months',strtotime(now()))),
                      ]);
                      /*$data['id']=$user->userid;
                      $data['amount']=$user->amount;
                      $data['txnid']=$walletTransferEntry;*/
                      //event(new \App\Events\BasicDeposite($data));
                    }elseif($user->comments=='fixed'){
                      $promotionalAdd=\App\FixedStackingDeposite::create([
                            'userid'    =>  $user->userid,
                            'planid'    =>  $user->planid,
                            'amount'    =>  $amount,
                        ]);
                      $promotionalAdd->increment('amount',$amount);
                      $plan=\App\FixedStackingDetail::where('id',$user->planid)->first();
                      $walletTransferEntry=\App\WalletTransfer::insertGetId([
                        'txnid'  =>  $user->id,
                        'fromWallet'  =>  'deposite',
                        'toWallet'  =>  'fixed',
                        'amount'  =>  $amount,
                        'release_date'    => date('Y-m-d',strtotime('+ '.$plan->duration.' months',strtotime(now()))),
                      ]);
                    }elseif($user->comments=='wallet'){
                      $promotionalAdd=\App\AccountDeposit::firstOrNew([
                            'userid'    =>  $user->userid,
                        ]);
                      if(!is_null($promotionalAdd->amount)){
                        $amt=Crypt::decrypt($promotionalAdd->amount)+($amount);
                      }else{
                        $amt=($amount);
                      }
                      $promotionalAdd->amount=(Crypt::encrypt($amt));
                      $promotionalAdd->save();
                      $walletTransferEntry=\App\WalletTransfer::insertGetId([
                        'userid'  =>  $user->userid,
                        'txnid'  =>  $user->id,
                        'fromWallet'  =>  'deposite',
                        'toWallet'  =>  'wallet',
                        'amount'  =>  ($amount),
                        'release_date'    => date('Y-m-d'),
                      ]);

                      //25 % extra 
                      /*if($amount>100){
                        $walletTransferEntry=\App\WalletTransfer::insertGetId([
                          'userid'  =>  $user->userid,
                          'txnid'  =>  $user->id,
                          'fromWallet'  =>  'Bonus 25',
                          'toWallet'  =>  'nWallet',
                          'amount'  =>  ($priceUpd*$amount*0.25),
                          'release_date'    => date('Y-m-d'),
                          'fromUser'  =>  1,
                        ]);
                        $networkWalletEntry=\App\NetworkingWallet::firstOrNew([
                              'userid'    =>  $user->userid,
                          ]);
                        if(!is_null($networkWalletEntry->amount)){
                          $amt=Crypt::decrypt($networkWalletEntry->amount)+($priceUpd*$amount*0.25);
                        }else{
                          $amt=($priceUpd*$amount*0.25);
                        }
                        $networkWalletEntry->amount=(Crypt::encrypt($amt));
                        $networkWalletEntry->save();
                      }*/


                    }
                  }//New Txngeneration in remaining
                  /*elseif($remaining>0){
                      $updi=\App\TransactionDetail::where('id',$user->id)->update(['remaining'=>$remaining,'paymentstatus'=> 0]);
                      $insexter=\App\TransactionInfo::create([
                                  'txnid' => $user->id,
                                  'payment_addr'  => $user->toaddress,
                                  'transaction_hash'   => '',
                                  'txn_status'  => 0,
                                  'contract_addr'  =>  env($user->currency.'_contract_addr'),
                              ]);
                      $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                  }*/
                }
                else{
                  $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>3,'amount'=>0]);
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3,]);
                }
              }
            }
        curl_close($curl);
    }

    public function erc20TokenTransaction($user){

        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.etherscan.io/api?module=proxy&action=eth_getTransactionReceipt&txhash=".str_replace(" ","",$user->txnhash)."&apikey=NJ8AVE24AK1N9MQ6B88JVKAGPKRBGJMZ13",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            $data=json_decode($response,true);//\Log::info($data);
            if(!is_array($data) || isset($data["error"])){
              \Log::info('Error for txnid '.$user->id.' txnhash '.$user->txnhash);
              $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0,'transaction_hash'=>'']);
                $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
              //continue;
            }else{
              $contract=$data['result']['logs'][0]['address'];
              $priceUpd=$user->amount/$user->amountusdt;
              /*if($data['contractType']==31){
                  $toAddr=$data['tokenTransferInfo']['to_address'];
              }
              else{
                  $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0,'transaction_hash'=>'']);
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
                //continue;
              }*/
              if(hexdec($data["result"]["status"])==1)
              {
                  $status=3;
              }else
              {
                  $status=4;
              }\Log::info('Status '.$status);
              if($contract!=env($user->currency.'_contract_addr')){
                $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['txn_status'=>0,'amount'=>0]);
                  $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>0]);
                //break;
              }else{
                $fromAddr=$data["result"]["from"];
                $toAddr='0x'.(substr($data["result"]["logs"][0]['topics'][2],26));
                $noOfToken=(hexdec($data["result"]["logs"][0]["data"])/1000000);
                \Log::info('from Address '.$fromAddr.' To addr '.$toAddr.' nooftoken '.$noOfToken);
                $amount=$noOfToken;
                $remaining=0;
                if($noOfToken>$user->remaining || $noOfToken==($user->remaining)){
                    $amount=$noOfToken;
                }/*elseif($noOfToken<($user->remaining)){
                  $amount=$noOfToken;
                  $remaining=$user->remaining-$noOfToken;
                }*/\Log::info('amount '.$amount.' remaining '.$remaining);
                if($status==3){
                  $toa=strcasecmp($user->toaddress,$toAddr);\Log::info('toa ' .$toa);
                  if($toa==0){
                    $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                    if($remaining==0){
                      $updi=\App\TransactionDetail::where('id',$user->id)->update(['release_date'=>now(),'paymentstatus'=>2,'remaining'=>0,'amountsftc'=>($priceUpd*$amount),'amountusdt'=>$amount]);

                      $udupdate=\App\UserDetails::where('id',$user->userid)->update([
                        'userstatus'=>1,
                        'capping'=>0,
                      ]);
                      if($user->comments=='basic'){
                          $udupdate=\App\UserDetails::where('id',$user->userid);
                          $udupdate->increment('userstate');
                          $udupdate->increment('total_investment',$user->amount);
                          $udupdate->increment('current_investment',$user->amount);
                          $udupdate->increment('total_self_investment',$user->amount);
                          $udupdate->increment('current_self_investment',$user->amount);
                          $basicWalletUpdate=\App\BasicWallet::firstOrCreate(['userid'=>$user->userid]);
                          $basicWalletUpdate->increment('amount',$user->amount);
                          $walletTransferEntry=\App\WalletTransfer::insertGetId([
                              'userid'  =>  $user->userid,
                              'fromWallet'  =>  'deposite',
                              'toWallet'  =>  'Basic',
                              'amount'  =>  $user->amount,
                          ]);
                          $data['id']=$user->userid;
                          $data['amount']=$user->amount;
                          $data['txnid']=$walletTransferEntry;
                          event(new \App\Events\BasicDeposite($data));
                      }elseif($user->comments=='Funding'){
                          $promotionalAdd=\App\PromotionalWallet::firstOrCreate(['userid'=>$user->userid]);
                          $promotionalAdd->increment('amount',$user->amount);
                          $walletTransferEntry=\App\WalletTransfer::create([
                              'userid'  =>  $user->userid,
                              'fromWallet'  =>  'deposite',
                              'toWallet'  =>  'Funding',
                              'amount'  =>  $user->amount,
                          ]);
                      }elseif($user->comments=='wallet'){
                        $promotionalAdd=\App\AccountDeposite::firstOrNew([
                              'userid'    =>  $user->userid,
                          ]);
                        if(!is_null($promotionalAdd->amount)){
                          $amt=Crypt::decrypt($promotionalAdd->amount)+($priceUpd*$amount);
                        }else{
                          $amt=($priceUpd*$amount);
                        }
                        $promotionalAdd->amount=(Crypt::encrypt($amt));
                        $promotionalAdd->save();
                        $walletTransferEntry=\App\WalletTransfer::insertGetId([
                          'userid'  =>  $user->userid,
                          'txnid'  =>  $user->id,
                          'fromWallet'  =>  'deposite',
                          'toWallet'  =>  'wallet',
                          'amount'  =>  ($priceUpd*$amount),
                          'release_date'    => date('Y-m-d'),
                        ]);

                        //25 % extra 
                        /*if($amount>100){
                          $walletTransferEntry=\App\WalletTransfer::insertGetId([
                            'userid'  =>  $user->userid,
                            'txnid'  =>  $user->id,
                            'fromWallet'  =>  'Bonus 25',
                            'toWallet'  =>  'nWallet',
                            'amount'  =>  ($priceUpd*$amount*0.25),
                            'release_date'    => date('Y-m-d'),
                            'fromUser'  =>  1,
                          ]);
                          $networkWalletEntry=\App\NetworkingWallet::firstOrNew([
                                'userid'    =>  $user->userid,
                            ]);
                          if(!is_null($networkWalletEntry->amount)){
                            $amt=Crypt::decrypt($networkWalletEntry->amount)+($priceUpd*$amount*0.25);
                          }else{
                            $amt=($priceUpd*$amount*0.25);
                          }
                          $networkWalletEntry->amount=(Crypt::encrypt($amt));
                          $networkWalletEntry->save();
                        }*/
                      }
                    }elseif($remaining>0){
                        $updi=\App\TransactionDetail::where('id',$user->id)->update(['remaining'=>$remaining,'paymentstatus'=> 0]);
                        $insexter=\App\TransactionInfo::create([
                                    'txnid' => $user->id,
                                    'payment_addr'  => $user->toaddress,
                                    'transaction_hash'   => '',
                                    'txn_status'  => 0,
                                    'contract_addr'  =>  env($user->currency.'_contract_addr'),
                                ]);
                        $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>2,'amount'=>$noOfToken]);
                    }
                  }
                  else{
                    $upd=\App\TransactionInfo::where('transaction_hash',$user->txnhash)->update(['payee_addr'=>$fromAddr,'txn_status'=>3,'amount'=>0]);
                    $updi=\App\TransactionDetail::where('id',$user->id)->update(['paymentstatus'=>3,]);
                  }
                }
              }
            }
            
        curl_close($curl);
    }



    public function npGatewayTansactionStatus(){
        $transactions=\App\TransactionDetail::where([['paymentstatus','<',2],['comments','!=','wallet'],['txntype',0]])->get();
        foreach ($transactions as $txn) {
            $npObject=new NPController();
            $paymentCreation=json_decode($npObject->getPaymentStatus($txn->comments));
            $transaction=\App\TransactionDetail::where('id',$txn->id)->first();
            if($paymentCreation->payment_status=='failed' || $paymentCreation->payment_status=='refunded' || $paymentCreation->payment_status=='expired'){
                $transactionDetailUpate=\App\TransactionDetail::where('id',$transaction->id)->update([
                    'paymentstatus'  =>   3,
                    'updated_at'    =>  now(),
                ]);
                $transactionInfoUpdate=\App\TransactionInfo::where('txnid',$transaction->id)->update([
                    'txn_status'  =>  3,
                    'updated_at'    =>  now(),
                ]);
                //return redirect('/User/Deposit')->with('warning','Your previous transaction either failed or refunded due to some reasons. Please initiate new transaction.');
            }elseif($paymentCreation->payment_status=='finished' || $paymentCreation->payment_status=='confirmed' || $paymentCreation->payment_status=='sending'){
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
                $sendMail=new SupportQueryController();
                try{
                    $details['email']=$transaction->userDetail()->user()->email;
                    $details['subject']='Your deposit confirmed at Cyera AI';
                    $details['view']='depositmail';
                    $details['amount']=$paymentCreation->actually_paid;
                    $details['userid']=$transaction->userDetail()->user()->uuid;
                    $status=$sendMail->sendMailgun($details);
                }catch(Exception $e){
                    \Log::info('Error in sending Topupmail for userid '.$transaction->userid);
                    \Log::info($e->messages());
                }
            }
        }
    }

    
}
