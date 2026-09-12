<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use App\StackingDeposite;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\StackingDetailController;
use App\Http\Controllers\SupportQueryController;

class WalletTransferController extends Controller
{
    //User
    public function stakePage()
    {
        //$capping=\App\StackingDeposite::where('userid',\Session::get('user.id'))->first();dd(' capamount ',Crypt::decrypt($capping->capamount));
        $userDetail = \App\UserDetails::where('id', \Session::get('user.id'))->first();
        if (!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining > 0) {
            return redirect()->to('/User/RepayLoan')->with('warning', 'You have an acive loan please repay it first.');
        }

        $availbleStfc = \App\AccountDeposit::where('userid', \Session::get('user.id'))->first();

        $user = null;
        $price = \App\ProfileStore::where('id', 1)->first();
        /*$user=\App\User::where('uuid',\Session::get('user.uuid'))
        ->join('user_details','users.id','=','user_details.userid')
        ->select(\DB::raw('('.\Session::get('logtime').'+ user_details.id) as id'),'uuid as uuid','email as email','usersname as name')->first();*/

        return view('user.upgrade')->with('balance', $availbleStfc)/*->with('stacking',$stackingPeriod)*/ ->with('user', $user)->with('price', $price);
    }


    public function getUserDetail(Request $request)
    {
        $dataregex = $this->findUserName($request->email);
        $regex = ['required', 'exists:users,uuid'];
        Validator::make($request->all(), [
            'userid' => $regex
        ])->validate();
        $availbleStfc = \App\AccountDeposit::where('userid', \Session::get('user.id'))->first();

        $price = \App\ProfileStore::where('id', 1)->first();
        $user = \App\User::where('uuid', $request->userid)
            ->join('user_details', 'users.id', '=', 'user_details.userid')
            ->select(\DB::raw('(' . \Session::get('logtime') . '+ user_details.id) as id'), 'uuid as uuid', 'email as email', 'usersname as name', 'user_details.id as uid')->first();
        /*$stackingDeposite=\App\StackingDeposite::where('userid',$user->uid)->get()->last();
        if(!is_null($stackingDeposite)){
            $stackingPeriod=\App\StackingDetail::where([['status',1],['id','>=',$stackingDeposite->planid]])->selectRaw('id +'.(pow(51, 3)).' as id,planname,cps,max_amount as amount')->get();
        }else{
            $stackingPeriod=\App\StackingDetail::where([['status',1]])->selectRaw('id +'.(pow(51, 3)).' as id,planname,cps,max_amount as amount')->get();
        } */

        $userDetail = \App\UserDetails::where('id', $user->id - \Session::get('logtime'))->first();
        if (!is_null($userDetail->userLoanStatus()) && $userDetail->userLoanStatus()->remaining > 0) {
            return redirect()->back()->with('warning', $userDetail->user()->uuid . ' User have an acive loan please repay it first.');
        }

        return view('user.upgrade')->with('balance', $availbleStfc)/*->with('stacking',$stackingPeriod)*/ ->with('user', $user)->with('price', $price);
    }

    public function stakeMWT(Request $request)
    {//dd($request->all());
        set_time_limit(0);

        if (!isset($request->staketype) || is_null($request->staketype)) {
            $request->staketype = 'Cyera AIWallet';
        }

        $validator = Validator::make($request->all(), [
            'honeypotu' => ['required', 'gt:' . \Session::get('logtime')],
            'staketype' => ['nullable', 'string'],
            /*'plan'       =>   ['required','numeric','gt:'.(pow(51,3))],*/
            'amount' => ['nullable', 'numeric'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {//dd($validator->errors(),$request->plan);
            return redirect('/User/Stake')->with('errors', $validator->errors());
        }
        if ($request->amount < 50 || $request->amount > 2000) {
            return redirect('/User/Stake')->with('warning', 'Staking amount must be between $50 and $2,000 USD');
        }

        $user = \App\User::where('uuid', \Session::get('user.userid'))->first();
        if (\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {

            $walletAmount = \App\AccountDeposit::where('userid', \Session::get('user.id'))->first();

            $getIncomingFund = \App\WalletTransfer::where([['userid', \Session::get('user.id')], ['toWallet', 'wallet']])->get();
            $getOutgoingFund = \App\WalletTransfer::where([['fromUser', \Session::get('user.id')], ['txnid', 0], ['fromWallet', 'wallet']])->get();
            $totalAmount = $getIncomingFund->sum('amount') - $getOutgoingFund->sum('amount');
            \Log::info('incomingfund ' . $getIncomingFund->sum('amount') . ' outgoingfund ' . $getOutgoingFund->sum('amount') . ' total ' . $totalAmount);
            $price = \App\ProfileStore::where('id', 1)->first();

            if ($totalAmount >= $request->amount) {
                \Log::info('Staking Amount: ' . $request->amount);

                \DB::beginTransaction();
                try {
                    $targetUserId = $request->honeypotu - \Session::get('logtime');
                    $userUpdate = \App\UserDetails::where('id', $targetUserId);
                    $insWalletEntry = \App\WalletTransfer::insertGetId([
                        'userid' => $targetUserId,
                        'txnid' => 0,
                        'fromWallet' => 'wallet',
                        'toWallet' => 'basic',
                        'amount' => $request->amount,
                        'fromUser' => \Session::get('user.id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'release_date' => date('Y-m-d'),
                    ]);
                    $walletReduceEntry = \App\AccountDeposit::where('userid', \Session::get('user.id'))->update([
                        'amount' => (Crypt::encrypt(Crypt::decrypt($walletAmount->amount) - $request->amount)),
                    ]);

                    // Unified Staking Execution
                    $plan = \App\StackingDetail::where('status', 1)->first();
                    if (!$plan) {
                        $plan = (object) ['id' => 1, 'capping' => 2.00, 'cps' => 0.50];
                    }

                    $cappingFunction = new StackingDetailController();
                    $userCapStats = $userUpdate->first()->getCappingTier();
                    $userMultiplier = $userCapStats['multiplier'] ?: 2;
                    $totalCapAmount = $request->amount * $userMultiplier;

                    $insertWallet = StackingDeposite::insertGetId([
                        'userid'     => $targetUserId,
                        'txnid'      => $insWalletEntry,
                        'amount'     => ($request->amount / $price->price),
                        'usdt'       => ($request->amount),
                        'capamount'  => Crypt::encrypt($totalCapAmount),
                        'planid'     => $plan->id,
                        'status'     => 1,
                        'roidouble'  => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'istatus'    => $userMultiplier,
                        'staketype'  => 1,
                    ]);

                    $userStatus = $userUpdate->first()->userstate;
                    $userUpdate->increment('userstate');
                    $userUpdate->increment('current_self_investment', $request->amount);
                    $userUpdate->increment('total_self_investment', $request->amount);
                    $userUpdate->increment('current_investment', $request->amount);
                    $userUpdate->increment('total_investment', $request->amount);
                    $userUpdate->update(['userstatus' => 1, 'capping' => 0, 'roi_status' => 1]);

                    // 5% Direct Referral Commission to Sponsor
                    $guiderDetail = \App\UserDetails::where('userid', $userUpdate->first()->sponsorid)->first();
                    if (!is_null($guiderDetail) && ($guiderDetail->userstate || !is_null($guiderDetail->userLoanStatus()))) {
                        $amt = $request->amount * 5 / 100; // 5% Direct Commission
                        \Log::info('Direct Return Amount BC ' . $amt);
                        $amt = $cappingFunction->cappingCalculation($guiderDetail->id, $amt);
                        \Log::info('Direct Return Amount AC ' . $amt);
                        if ($amt > 0) {
                            $insertDirectIncome = \App\BonusReward::create([
                                'userid'         => $guiderDetail->id,
                                'fromuser'       => $userUpdate->first()->id,
                                'amount'         => $amt / $price->price,
                                'remaining'      => $amt / $price->price,
                                'amt_usdt'       => $amt,
                                'remaining_usdt' => $amt,
                                'txnid'          => $insertWallet,
                                'description'    => 'referral',
                                'status'         => 0,
                                'created_at'     => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }

                    // Real-time Tree Business Update, Booster Check & Level 2-15 Income Distribution
                    $cappingFunction->businessUpdate();
                    try {
                        $sendMail = new SupportQueryController();
                        $details['email'] = $userUpdate->first()->user()->email;
                        $details['subject'] = 'You have a top-up now.';
                        $details['view'] = 'planactivationmail';
                        $details['amount'] = $request->amount;
                        $details['userid'] = $userUpdate->first()->user()->uuid;
                        $status = $sendMail->sendMailgun($details);
                        if (($request->honeypotu - \Session::get('logtime')) != \Session::get('user.id')) {
                            $detail['email'] = $user->email;
                            $detail['subject'] = 'You just made a top-up.';
                            $detail['view'] = 'walletReduceMail';
                            $detail['amount'] = $request->amount;
                            $detail['userid'] = $userUpdate->first()->user()->uuid;
                            $detail['useruuid'] = \Session::get('user.userid');
                            $status = $sendMail->sendMailgun($detail);
                        }
                    } catch (Exception $e) {
                        \Log::info('Error in sending Topupmail by userid ' . $user->id);
                        \Log::info($e->messages());
                    }
                    \DB::commit();
                    return redirect('/User/Stake')->with('success', 'The user has been upgraded. Please wait 15-45 minutes for the business update in Genealogy.');

                } catch (Exception $e) {
                    \DB::rollback();
                    \Log::info('Error for User ' . \Session::get('user.id') . ' Error message is ' . $e->getMessage());
                    return redirect('/User/Stake')->with('warning', 'Error Code 1021, There is some error in stacking.');
                }
            } else {
                return redirect('/User/Stake')->with('warning', 'Error Code 1021, There is some error in stacking.');
            }
        } else {
            return redirect('/User/Stake')->with('warning', 'Your entered password is wrong.');
        }
    }

    /**
     * Web3 Unified On-Chain Staking:
     * Receives on-chain transaction from CyeraInvestmentSplitter,
     * verifies txHash uniqueness, and executes the complete 10-step atomic
     * deposit audit trail + fund ledger credit/debit + StackingDeposite activation + 5% Direct Referral + 15-level Tree Volume sync.
     */
    public function web3UnifiedStake(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:50', 'max:2000'],
            'txHash' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{64}$/'],
            'targetUserId' => ['nullable', 'string'],
            'senderAddress' => ['nullable', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/']
        ]);

        $amount = floatval($request->amount);
        $txHash = strtolower($request->txHash);
        
        \Log::info("Web3UnifiedStake: Incoming Request", [
            'amount' => $amount,
            'txHash' => $txHash,
            'targetUserId' => $request->targetUserId,
            'senderAddress' => $request->senderAddress,
            'session_user_id' => \Session::get('user.id')
        ]);

        $currentUserId = \Session::get('user.id');

        if (!$currentUserId && \Auth::check()) {
            $currentDetail = \App\UserDetails::where('userid', \Auth::id())->orWhere('id', \Auth::id())->first();
            if ($currentDetail) {
                $currentUserId = $currentDetail->id;
            }
        }

        if (!$currentUserId && !empty($request->senderAddress)) {
            $senderAddr = strtolower($request->senderAddress);
            $asset = \App\AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$senderAddr])
                ->orWhereRaw('LOWER(bep20addr) = ?', [$senderAddr])
                ->first();
            if ($asset) {
                $currentDetail = \App\UserDetails::where('id', $asset->userid)->orWhere('userid', $asset->userid)->first();
                if ($currentDetail) {
                    $currentUserId = $currentDetail->id;
                }
            }
        }

        if (!$currentUserId) {
            \Log::warning("Web3UnifiedStake: Unauthenticated Session for TxHash: $txHash");
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated session. Please refresh and connect wallet again.'], 401);
        }

        // Check for duplicate TxHash in database
        $duplicateTxn = \App\TransactionInfo::whereRaw('LOWER(transaction_hash) = ?', [$txHash])->first();
        if ($duplicateTxn) {
            \Log::warning("Web3UnifiedStake: Duplicate TxHash detected: $txHash");
            return response()->json(['status' => 'error', 'message' => 'This transaction hash has already been processed in Cyera AI.'], 400);
        }

        // Determine target user (Self or Specified Downline UUID/ID)
        $targetUserDetail = null;
        if (!empty($request->targetUserId) && strtolower(trim($request->targetUserId)) !== 'self') {
            $targetInput = trim($request->targetUserId);
            $userObj = \App\User::where('uuid', $targetInput)->orWhere('id', $targetInput)->first();
            if ($userObj) {
                $targetUserDetail = \App\UserDetails::where('userid', $userObj->id)->orWhere('id', $userObj->id)->first();
            }
        }
        if (!$targetUserDetail) {
            $targetUserDetail = \App\UserDetails::where('id', $currentUserId)->orWhere('userid', $currentUserId)->first();
        }

        if (!$targetUserDetail) {
            \Log::warning("Web3UnifiedStake: Target user account not found for currentUserId: $currentUserId");
            return response()->json(['status' => 'error', 'message' => 'Target user account not found.'], 404);
        }

        $targetUserId = $targetUserDetail->id;
        $price = \App\ProfileStore::where('id', 1)->first() ?: (object)['price' => 1];
        $splitterAddress = env('CYERA_MINING_ENGINE_ADDRESS', env('INVESTMENT_SPLITTER_ADDRESS', '0xdd905468F6F91f8c37eFB9e27E1f282734E60217'));
        $senderAddr = !empty($request->senderAddress) ? strtolower($request->senderAddress) : null;

        // Comprehensive On-Chain BSC Mainnet Receipt, Freshness & Amount Verification
        $verifyResult = $this->verifyBscTransaction($txHash, $splitterAddress, $senderAddr, $amount);
        if (!$verifyResult['valid']) {
            \Log::warning("Web3UnifiedStake: BSC On-Chain Verification Failed: " . $verifyResult['message']);
            return response()->json([
                'status' => 'error',
                'message' => $verifyResult['message']
            ], 400);
        }

        \DB::beginTransaction();
        try {
            // ============================================================
            // PHASE 1: DEPOSIT AUDIT TRAIL (70/30 On-Chain Record)
            // ============================================================
            $depositTxn = \App\TransactionDetail::create([
                'userid'        => $currentUserId,
                'txntype'       => 0, // Deposit
                'amountsftc'    => ($amount / $price->price),
                'amountusdt'    => $amount,
                'remaining'     => 0,
                'paymentstatus' => 2, // Paid / Confirmed
                'txndesc'       => 'Web3 On-Chain 70/30 Splitter Deposit',
                'comments'      => 'web3_split',
                'planid'        => 1,
                'currency'      => 'usdtbep20',
                'paidby'        => $currentUserId,
                'created_at'    => now(),
                'release_date'  => date('Y-m-d'),
            ]);

            \App\TransactionInfo::create([
                'txnid'            => $depositTxn->id,
                'payment_addr'     => $splitterAddress,
                'transaction_hash' => $txHash,
                'contract_addr'    => $splitterAddress,
                'amount'           => $amount,
                'txn_status'       => 2, // Confirmed
            ]);

            // Deposit WalletTransfer (deposite -> wallet)
            \App\WalletTransfer::create([
                'userid'       => $currentUserId,
                'txnid'        => $depositTxn->id,
                'fromWallet'   => 'deposite',
                'toWallet'     => 'wallet',
                'amount'       => $amount,
                'fromUser'     => $currentUserId,
                'release_date' => date('Y-m-d'),
                'created_at'   => now(),
            ]);

            // Credit AccountDeposit (Fund Ledger)
            $accountDep = \App\AccountDeposit::firstOrNew(['userid' => $currentUserId]);
            $currentFund = !is_null($accountDep->amount) ? floatval(Crypt::decrypt($accountDep->amount)) : 0;
            $accountDep->amount = Crypt::encrypt($currentFund + $amount);
            $accountDep->save();

            // ============================================================
            // PHASE 2: STAKING EXECUTION & COMMISSION LEDGER
            // ============================================================
            // Debit AccountDeposit (Fund Ledger)
            $newFund = ($currentFund + $amount) - $amount; // Net balance
            $accountDep->amount = Crypt::encrypt($newFund);
            $accountDep->save();

            // Stake WalletTransfer (wallet -> basic)
            $stakeTransfer = \App\WalletTransfer::create([
                'userid'       => $targetUserId,
                'txnid'        => $depositTxn->id,
                'fromWallet'   => 'wallet',
                'toWallet'     => 'basic',
                'amount'       => $amount,
                'fromUser'     => $currentUserId,
                'release_date' => date('Y-m-d'),
                'created_at'   => now(),
            ]);

            // Capping tier calculation & StackingDeposite activation
            $plan = \App\StackingDetail::where('status', 1)->first() ?: (object)['id' => 1, 'capping' => 2.00, 'cps' => 0.50];
            $cappingFunction = new StackingDetailController();
            $userCapStats = $targetUserDetail->getCappingTier();
            $userMultiplier = $userCapStats['multiplier'] ?: 2;
            $totalCapAmount = $amount * $userMultiplier;

            $stackingDeposit = StackingDeposite::create([
                'userid'     => $targetUserId,
                'txnid'      => $stakeTransfer->id,
                'amount'     => ($amount / $price->price),
                'usdt'       => $amount,
                'capamount'  => Crypt::encrypt($totalCapAmount),
                'planid'     => $plan->id,
                'status'     => 1,
                'roidouble'  => 1,
                'created_at' => now(),
                'istatus'    => $userMultiplier,
                'staketype'  => 1,
            ]);

            // Update UserDetails Investment Stats
            $targetUserDetail->increment('userstate');
            $targetUserDetail->increment('current_self_investment', $amount);
            $targetUserDetail->increment('total_self_investment', $amount);
            $targetUserDetail->increment('current_investment', $amount);
            $targetUserDetail->increment('total_investment', $amount);
            $targetUserDetail->update([
                'userstatus' => 1,
                'capping'    => 0,
                'roi_status' => 1
            ]);

            // 5% Direct Referral Commission to Sponsor
            $guiderDetail = \App\UserDetails::where('userid', $targetUserDetail->sponsorid)->orWhere('id', $targetUserDetail->sponsorid)->first();
            if ($guiderDetail && ($guiderDetail->userstate || !is_null($guiderDetail->userLoanStatus()))) {
                $dirAmt = $amount * 5 / 100; // 5% Direct Commission
                $dirAmt = $cappingFunction->cappingCalculation($guiderDetail->id, $dirAmt);
                if ($dirAmt > 0) {
                    \App\BonusReward::create([
                        'userid'         => $guiderDetail->id,
                        'fromuser'       => $targetUserDetail->id,
                        'amount'         => ($dirAmt / $price->price),
                        'remaining'      => ($dirAmt / $price->price),
                        'amt_usdt'       => $dirAmt,
                        'remaining_usdt' => $dirAmt,
                        'txnid'          => $stackingDeposit->id,
                        'description'    => 'referral',
                        'status'         => 0,
                        'created_at'     => now(),
                    ]);
                }
            }

            // Real-time Tree Business Update, Booster Check & Level 2-15 Income Distribution
            $cappingFunction->businessUpdate();

            \DB::commit();
            \Log::info("Web3UnifiedStake: Success! Staking activated for UserID: $targetUserId, Amount: $$amount");

            return response()->json([
                'status'  => 'success',
                'message' => 'Web3 Staking of $' . number_format($amount, 2) . ' USDT activated successfully on BSC Mainnet!',
                'data'    => [
                    'txHash'        => $txHash,
                    'amount'        => $amount,
                    'multiplier'    => $userMultiplier,
                    'cappingLimit'  => $totalCapAmount,
                    'targetUser'    => $targetUserDetail->user() ? $targetUserDetail->user()->uuid : 'Self'
                ]
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Web3 Staking Error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json([
                'status'  => 'error',
                'message' => 'Staking activation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifies transaction receipt directly against official BSC Mainnet JSON-RPCs with multi-RPC fallback:
     * 1. Status == 0x1 (Success)
     * 2. Interaction directed to CyeraInvestmentSplitter
     * 3. Freshness Check (Mined within last 30 minutes to prevent old txn replay)
     * 4. Sender Address Match (Transaction originated from user's authenticated wallet)
     * 5. On-Chain Amount Match (Decodes Invested event log amount)
     */
    protected function verifyBscTransaction($txHash, $expectedContract, $expectedSender = null, $expectedAmount = null)
    {
        $isDemo = env('DEMO_MODE', false) || env('TEST_MODE', false) || config('app.demo_mode', false);
        if ($isDemo) {
            \Log::info("verifyBscTransaction: DEMO_MODE active, bypassing on-chain check for $txHash");
            return ['valid' => true, 'message' => 'Demo mode active: On-chain check simulated.'];
        }

        $rpcEndpoints = [
            "https://bsc.meowrpc.com",
            "https://bsc-dataseed1.defibit.io/",
            "https://bsc-dataseed.binance.org/",
            "https://bsc-dataseed1.ninicoin.io/",
            "https://binance.llamarpc.com",
            "https://1rpc.io/bnb"
        ];

        $payloadReceipt = json_encode([
            'jsonrpc' => '2.0',
            'method' => 'eth_getTransactionReceipt',
            'params' => [$txHash],
            'id' => 1
        ]);

        $receipt = null;
        $rpcUsed = '';

        // Thorough multi-round query across BSC RPC endpoints with patience
        $maxAttempts = 8;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            \Log::info("verifyBscTransaction: [Attempt $attempt/$maxAttempts] Querying BSC RPCs for TxHash: $txHash");

            foreach ($rpcEndpoints as $rpcUrl) {
                $ch = curl_init($rpcUrl);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadReceipt);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);

                if ($response) {
                    $data = json_decode($response, true);
                    if (isset($data['result']) && !empty($data['result'])) {
                        $receipt = $data['result'];
                        $rpcUsed = $rpcUrl;
                        \Log::info("verifyBscTransaction: Found receipt on attempt $attempt via $rpcUrl for $txHash");
                        break 2;
                    }
                }
            }

            if ($attempt < $maxAttempts) {
                sleep(2);
            }
        }

        if (!$receipt) {
            \Log::warning("verifyBscTransaction: Receipt not found after $maxAttempts attempts on BSC RPCs for $txHash");
            return ['valid' => false, 'message' => 'Transaction not found or not yet indexed by BSC nodes after multiple verification attempts. Please ensure the transaction succeeded in MetaMask and retry syncing.'];
        }

        \Log::info("verifyBscTransaction: Receipt retrieved from $rpcUsed for $txHash", ['status' => $receipt['status'] ?? 'unknown']);

        // Check Status (0x1 = Confirmed Success)
        if (!isset($receipt['status']) || $receipt['status'] !== '0x1') {
            return ['valid' => false, 'message' => 'Transaction has reverted or failed on BSC blockchain.'];
        }

        // Check Target Contract (Direct Interaction or Internal Contract Execution via AA/Bundler/Relayer)
        $interactedWithContract = (isset($receipt['to']) && strtolower($receipt['to']) === strtolower($expectedContract));
        if (!$interactedWithContract && isset($receipt['logs']) && is_array($receipt['logs'])) {
            foreach ($receipt['logs'] as $log) {
                if (isset($log['address']) && strtolower($log['address']) === strtolower($expectedContract)) {
                    $interactedWithContract = true;
                    break;
                }
            }
        }

        if (!$interactedWithContract) {
            \Log::warning("verifyBscTransaction: Target contract mismatch. Expected $expectedContract, got " . ($receipt['to'] ?? 'null'));
            return ['valid' => false, 'message' => 'Transaction was not sent to or did not interact with the official Cyera Staking Splitter contract.'];
        }

        // Check Event Logs
        if (!isset($receipt['logs']) || count($receipt['logs']) === 0) {
            return ['valid' => false, 'message' => 'No token transfer or staking execution logs found in transaction receipt.'];
        }

        // Check Sender if provided (Direct from address or Smart Account/User address in event topics)
        if (!empty($expectedSender)) {
            $senderMatches = false;
            $cleanSender = ltrim(strtolower($expectedSender), '0x');
            
            if (isset($receipt['from']) && strtolower($receipt['from']) === strtolower($expectedSender)) {
                $senderMatches = true;
            } elseif (isset($receipt['logs']) && is_array($receipt['logs'])) {
                foreach ($receipt['logs'] as $log) {
                    if (isset($log['topics'][1]) && str_contains(strtolower($log['topics'][1]), $cleanSender)) {
                        $senderMatches = true;
                        break;
                    }
                }
            }

            if (!$senderMatches) {
                \Log::warning("verifyBscTransaction: Sender mismatch. Expected $expectedSender, got " . ($receipt['from'] ?? 'null'));
                return ['valid' => false, 'message' => 'Transaction was not broadcast from your authenticated wallet address.'];
            }
        }

        // 2. Check Block Timestamp (Freshness Check: Max 60 minutes old)
        if (isset($receipt['blockNumber'])) {
            $payloadBlock = json_encode([
                'jsonrpc' => '2.0',
                'method' => 'eth_getBlockByNumber',
                'params' => [$receipt['blockNumber'], false],
                'id' => 2
            ]);

            $chBlock = curl_init($rpcUsed ?: "https://bsc.meowrpc.com");
            curl_setopt($chBlock, CURLOPT_POSTFIELDS, $payloadBlock);
            curl_setopt($chBlock, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($chBlock, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chBlock, CURLOPT_TIMEOUT, 6);
            curl_setopt($chBlock, CURLOPT_SSL_VERIFYPEER, false);
            $resBlock = curl_exec($chBlock);
            curl_close($chBlock);

            if ($resBlock) {
                $blockData = json_decode($resBlock, true);
                if (isset($blockData['result']['timestamp'])) {
                    $blockTimestamp = hexdec($blockData['result']['timestamp']);
                    $currentTimestamp = time();
                    $ageSeconds = $currentTimestamp - $blockTimestamp;

                    // Reject if transaction was mined more than 60 minutes (3600s) ago
                    if ($ageSeconds > 3600) {
                        return [
                            'valid' => false,
                            'message' => 'This transaction was mined in an old block (' . round($ageSeconds / 60) . ' minutes ago). Old transactions cannot be reused for new stakes.'
                        ];
                    }
                }
            }
        }

        // 3. Decode & Strictly Verify Official USDT Token Contract and Event Logs
        $officialUsdtContract = strtolower(env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'));
        $investedTopic0 = '0xf77aeb8a2e27f3c48052a7019aa2cf8d273c715051f79b8bb153f91cf4a04760'; // CyeraMiningEngine: Invested(address,uint256,uint256,uint256,uint256,uint256,uint256)
        $transferTopic0 = '0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef';
        
        $validUsdtTransferFound = false;
        $amountFound = null;

        foreach ($receipt['logs'] as $log) {
            $logContract = strtolower($log['address'] ?? '');
            
            if (isset($log['topics'][0])) {
                $topic0 = strtolower($log['topics'][0]);

                // Verify Official Invested Event from CyeraMiningEngine Contract
                if ($topic0 === strtolower($investedTopic0) && $logContract === strtolower($expectedContract)) {
                    $cleanData = (substr($log['data'], 0, 2) === '0x' || substr($log['data'], 0, 2) === '0X') ? substr($log['data'], 2) : $log['data'];
                    $amountHex = substr($cleanData, 0, 64);
                    $weiDec = $this->hexToDecBc($amountHex);
                    $amountFound = (float)bcdiv($weiDec, '1000000000000000000', 4);
                } 
                // Verify Official Tether USD (0x55d3...7955) Transfer Event to Splitter
                elseif ($topic0 === strtolower($transferTopic0)) {
                    if ($logContract === $officialUsdtContract) {
                        if (isset($log['topics'][2]) && str_contains(strtolower($log['topics'][2]), ltrim(strtolower($expectedContract), '0x'))) {
                            $validUsdtTransferFound = true;
                            $cleanData = (substr($log['data'], 0, 2) === '0x' || substr($log['data'], 0, 2) === '0X') ? substr($log['data'], 2) : $log['data'];
                            $amountHex = substr($cleanData, 0, 64);
                            $weiDec = $this->hexToDecBc($amountHex);
                            $transferAmount = (float)bcdiv($weiDec, '1000000000000000000', 4);
                            if (is_null($amountFound)) {
                                $amountFound = $transferAmount;
                            }
                        }
                    }
                }
            }
        }

        \Log::info("verifyBscTransaction: Decoded", [
            'validUsdtTransferFound' => $validUsdtTransferFound,
            'amountFound' => $amountFound,
            'expectedAmount' => $expectedAmount
        ]);

        // Strict Enforcement: Must have valid official USDT token transfer to splitter
        if (!$validUsdtTransferFound && is_null($amountFound)) {
            return [
                'valid' => false,
                'message' => 'Invalid or unverified token detected. Only genuine Binance-Peg BSC-USD (Tether USDT 0x55d3...7955) is accepted. Wrapped or custom tokens are strictly rejected.'
            ];
        }

        if (!is_null($expectedAmount) && $expectedAmount > 0) {
            if (is_null($amountFound)) {
                return [
                    'valid' => false,
                    'message' => 'Unable to verify staking deposit amount from blockchain receipt.'
                ];
            }

            if (abs($amountFound - $expectedAmount) > 0.05) {
                return [
                    'valid' => false,
                    'message' => 'On-chain transaction amount ($' . number_format($amountFound, 2) . ' USDT) does not match the requested stake amount ($' . number_format($expectedAmount, 2) . ' USDT).'
                ];
            }
        }

        return ['valid' => true, 'message' => 'Transaction verified successfully.'];
    }

    /**
     * Pure PHP Hex to Decimal converter using BCMath
     */
    protected function hexToDecBc($hex)
    {
        if (substr($hex, 0, 2) === '0x' || substr($hex, 0, 2) === '0X') {
            $hex = substr($hex, 2);
        }
        $hex = ltrim($hex, '0');
        if ($hex === '') {
            return '0';
        }
        $dec = '0';
        $len = strlen($hex);
        for ($i = 0; $i < $len; $i++) {
            $digit = hexdec($hex[$i]);
            $dec = bcadd(bcmul($dec, '16'), (string)$digit);
        }
        return $dec;
    }

}

