<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\UserDetails;
use App\TransactionDetail;
use App\TransactionInfo;
use DB;
use Session;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AssetDetailChangesController extends Controller
{
    public function failedWithdrawApi(){

    }

    public function resendWithdrawOtp($userid){
        $getWithdraw=\App\TransactionDetail::where([['userid',$userid],['txntype',1],['paymentstatus','<',2],['planid',0]])->first();
        if(!is_null($getWithdraw))
        {
            $userDetail=\App\UserDetails::where('userid',$userid)->first();
            $randId=rand(111111,999999);
            $updateTxnInfo=\App\TransactionInfo::where('txnid',$getWithdraw->id)->update([
                'transaction_hash'  =>  $randId,
            ]);
            $mailStatus=0;
            $data['token']=$randId;
            $data['email']=$userDetail->user()->email;
            $data['view']='withdrawOtpMail';
            $data['subject']='OTP to confirm withdraw request.';
            $data['useruuid']=$userDetail->user()->uuid;
            $data['currency']=$getWithdraw->currency;
            $data['address']=$getWithdraw->transactionInfo()->first()->payment_addr;
            $data['amountusdt']=$getWithdraw->amountusdt;
            $data['amountcoin']=$getWithdraw->amount;
            $mailSend=new SupportQueryController();
            try{
                $mailStatus=$mailSend->sendMailgun($data);
            }
            catch(Exception $e){
                \Log::info('error in sending mails in resend mail of withdraw for userid '.$userid);
                \Log::info(' Error message is '.$e->getMessage());
                return 0;
            }
            $txnIdUpdate=\App\TransactionDetail::where('id',$getWithdraw->id)->update([
                'b_status'  =>  ($mailStatus==0)?1:2,
            ]);
            return 1;
        }else{
            return 0;
        }
    }

    public function resendProfileEditOtp($userid){
        $editstatus=\App\AssetDetailChanges::where([['userid',$userid],['asset_status','>',0]])->first();
        if(!is_null($editstatus)){
            $response=0;
            $user=\App\UserDetails::where('id',$userid)->first();
            $userdetail['userid']=$userid;
            $userdetail['token']=rand(100000,999999);
            $userdetail['email']=$user->user()->email;
            $userdetail['view']="otpMail";
            $userdetail['subject']="OTP to confirm profile changes";
            $userdetail['useruuid']=$user->user()->uuid;
            $mail=new SupportQueryController();
            try{
                $response=$mail->sendMailgun($userdetail);
                if($response==0)
                    $status=1;
                else
                    $status=2;
                $updstatus=\App\AssetDetailChanges::where('id',$editstatus->id)->update(['token'=> $userdetail['token'],'asset_status'=>$status,'updated_at'=>now()]);
            }catch(Exception $e){
                \Log::info('error in sending mails in resend mail of edit profile for userid '.$userid);
                \Log::info(' Error message is '.$e->getMessage());
                return 0;
            }
            return 1;
        }else{
            return 0;
        }
    }

    public function resendWithdrawOtpWeb(){
        $status=$this->resendWithdrawOtp(\Session::get('user.id'));
        if($status==0){
            return redirect()->back()->with('warning','There is something went wrong. Please try again after sometime.');
        }else{
            return redirect()->back()->with('success','Email sent sucessfully.');
        }
    }

    public function resendProfileEditOtpWeb(){
        $status=$this->resendProfileEditOtp(\Session::get('user.id'));
        if($status==0){
            return redirect()->back()->with('warning','There is something went wrong. Please try again after sometime.');
        }else{
            return redirect()->back()->with('success','Email sent sucessfully.');
        }
    }


    public function getTotal(Request $request)
    {
        set_time_limit(0);

        $level = $request->get('level', 1);
        $perPage = 50;

        $usrid = DB::table('user_details')
            ->where('id', Session::get('user.id'))
            ->value('userid');

        if (!$usrid) {
            return view('user.teamtotal')
                ->with('Warning', 'User Does Not Exist');
        }

        $uids = [$usrid];

        // sirf selected level tak hi loop chale
        for ($i = 1; $i < $level; $i++) {
            $uids = DB::table('user_details')
                ->whereIn('sponsorid', $uids)
                ->pluck('userid')
                ->toArray();

            if (empty($uids)) break;
        }

        // pagination directly query par
        $totaldown = User::where('users.permission', '1')
            ->join('user_details as ud', 'users.id', '=', 'ud.userid')
            ->whereIn('ud.sponsorid', $uids)
            ->select(
                'users.id as id',
                'users.usersname as name',
                'users.uuid as userid',
                'ud.current_self_investment as current',
                'ud.leveluser as leveluser'
            )
            ->selectRaw('"Level-'.$level.'" as level')
            ->selectRaw('DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
            ->selectRaw('CASE WHEN ud.userstatus=1 THEN "Active" ELSE "Inactive" END as status')
            ->selectRaw('CASE WHEN ud.userstatus=1 THEN "3cd2a5" ELSE "FF0000" END as statusclass')
            ->paginate($perPage);

        return view('user.teamtotal', compact('totaldown', 'level'));
    }


    public function SearchTeamBusinessPage(){
        return view('user.searchteambusiness')->with('data',array());
    }

    public function SearchUserTeamBusinessDate(Request $request)
    {
        set_time_limit(0);

        $user_self = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.id', Session::get('user.id'))
            ->value('users.id');

        if(!$user_self){
            return redirect('/User/SearchTeamBusiness')->with('warning','User Not Found')->with('data',array());
        }

        // STEP 2: BFS on USERS.ID
        $team_user_self = [];
        $queueself = [$user_self];

        while(!empty($queueself)){
            // Find children self
            $childrenself = DB::table('user_details')
                ->whereIn('sponsorid', $queueself)     // sponsorid = users.id
                ->pluck('userid')                  // userid = users.id
                ->toArray();

            if(empty($childrenself)) break;

            $team_user_self = array_merge($team_user_self, $childrenself);
            $queueself = $childrenself;
        }


        $fromDate = $request->fromdate ?? date('Y-m-d');
        $toDate = ($request->todate ?? date('Y-m-d')) . ' 23:59:59';

        // STEP 1: Identify user (users.id for BFS)
        $datareg = $this->findUserName($request->userrid);

        $user_account_id = DB::table('user_details')
            ->join('users','users.id','=','user_details.userid')
            ->where('users.' . $datareg['type'], $request->userrid)
            ->value('users.id');

        if(!$user_account_id){
            return redirect('/User/SearchTeamBusiness')->with('warning','User Not Found')->with('data',array());
        }

        array_unshift($team_user_self, $user_self);

        if(!in_array($user_account_id, $team_user_self)){
            return redirect('/User/SearchTeamBusiness')->with('warning','User is not in your downline')->with('data',array());
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
            return view('user.searchteambusiness')
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
            return view('user.searchteambusiness')
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

        return view('user.searchteambusiness')
            ->with('data', $data)
            ->with('totalAmount', $total);
    }





}
