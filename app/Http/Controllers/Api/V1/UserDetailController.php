<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SupportQueryController;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use DB;

class UserDetailController extends BaseController
{
    public function dashboard(Request $request){
        $data['userDetail']=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->select('total_direct as totaldirect','active_direct as activedirect','total_downline as totaldownline','active_downline as activedownline','current_self_investment as myinvestment','total_direct_investment as directbusiness')
        ->selectRaw('(total_level_investment+total_direct_investment) as totalbusiness')->first();

        $data['username']=\App\User::where('id',$request->user()->id)->select('usersname as username','uuid as uuid',)->first();
        
        $data['withdrawAmount']=DB::table('transaction_details')->where([['transaction_details.userid',$request->user()->userDetails()->first()->id],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',2]])
        ->select(DB::raw('sum(amountusdt) as amountusdt'))
        ->get()->first();
        
        //$data['currentDeposit']=$request->user()->userDetails()->first()->stackingDeposite()->where('status','>',0)->sum('amount');
        $data['firstdeposit']=\App\StackingDeposite::where('userid',$request->user()->userDetails()->first()->id)->first();
        
        $data['totalIncome']=$request->user()->userDetails()->first()->totalIncomeUSDT();
        $data['unpaidIncome']=$request->user()->userDetails()->first()->remainingIncome();
        $data['lockedIncome']=$request->user()->userDetails()->first()->lockedIncome();
        $data['stakingReward']=$request->user()->userDetails()->first()->stackingIncome()->sum('amt_usdt');
        $data['directReward']=$request->user()->userDetails()->first()->bonusReward()->where('status','!=',3)->sum('amt_usdt');
        $data['stakingReferralReward']=$request->user()->userDetails()->first()->levelIncome()->where('description','l')->sum('amt_usdt');
        $data['teamDevelopmentIncome']=$request->user()->userDetails()->first()->levelIncome()->where('description','r')->sum('amt_usdt');
        $data['clubReward']=$request->user()->userDetails()->first()->clubIncome()->sum('amt_usdt');
        $data['lifetimeReward']=$request->user()->userDetails()->first()->lifetimeIncome()->sum('amount');
     	$data['doj']=$request->user()->doj;
        $data['remainingCapping']=round((is_null($request->user()->userDetails()->first()->remainingCapping())?0:$request->user()->userDetails()->first()->remainingCapping()),2);
        
        $availblewallet=\App\AccountDeposit::where('userid',$request->user()->userDetails()->first()->id)->first();
        if(!is_null($availblewallet)){
            $data['walletAmount']=round(Crypt::decrypt($availblewallet->amount),3);
        }else{
            $data['walletAmount']=0;
        }
        if ($request->user()->userDetails()->first()->booster==2) {
            $data['boosterStatus']="Active";
        }else{
            $data['boosterStatus']="Pending";
        }

        $firstdeposit=\App\StackingDeposite::where([['stacking_deposites.userid',$request->user()->userDetails()->first()->id],['wallet_transfers.fromWallet','!=','loan']])
        ->join('wallet_transfers','stacking_deposites.txnid','=','wallet_transfers.id')
        ->select('stacking_deposites.created_at as activationdate')->first();
        if (!is_null($firstdeposit)) {
            $data['activationdate']=$firstdeposit->activationdate;
        }else{
            $data['activationdate']=NULL;
        }

        if(!is_null($request->user()->userDetails()->first()->userLoanStatus())){
            $data['loanAmount']=$request->user()->userDetails()->first()->userLoanStatus()->amount;
            $data['dueLoanAmount']=$request->user()->userDetails()->first()->userLoanStatus()->remaining;
        }else{
            $data['loanAmount']=0;
            $data['dueLoanAmount']=0;
        }

        $userlevel=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first()->levelStatus();
        $adminlevel=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first()->leveluser;
        if ($userlevel>=$adminlevel) {
            $data['levelpercentage']=$userlevel;
        }else{
            $data['levelpercentage']=$adminlevel;
        }

        //$data['lockedIncome']=$request->user()->userDetails()->first()->lockedIncome();
        
        
        $data['referrallink']=url('/').'/register/'.$request->user()->uuid;
        
        $data['marquee']='Welcome to Cyera AI';

        $data['directList']=\App\UserDetails::where('sponsorid',$request->user()->userDetails()->first()->userid)
        ->join('users as u','user_details.userid','=','u.id')
        ->select(/*'u.id as id',*/ 'u.usersname as name', 'u.uuid as userID', 'u.doj as doj')
        ->selectRaw('case when user_details.userstatus=0 then "Unpaid" when user_details.userstatus=1 then "Paid" end as status')
        ->orderByRaw('u.id DESC')
        ->get();
        
        return $this->sendResponse($data,'Dashboard Data');
    }

    public function todayData(Request $request){
        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();

        $data['withdrawAmount']=DB::table('transaction_details')->where([['transaction_details.userid',$request->user()->userDetails()->first()->id],['transaction_details.txntype','1'],['transaction_details.txndesc','Withdrawal'],['transaction_details.paymentstatus',2],['transaction_details.created_at','>',date("Y-m-d")]])
        ->select(DB::raw('sum(amountusdt) as amountusdt'))
        ->get()->first();

        $data['todayStakingReward']=$userDetail->stackingIncome()->where('created_at','>',date("Y-m-d"))->sum('amt_usdt');
        $data['todayStakingReferralReward']=$userDetail->levelIncome()->where('description','l')->where('created_at','>',date("Y-m-d"))->sum('amt_usdt');
        $data['todayTeamDevelopmentReward']=$userDetail->levelIncome()->where('description','r')->where('created_at','>',date("Y-m-d"))->sum('amt_usdt');
        $data['todayClubReward']=$userDetail->clubIncome()->where('created_at','>',date("Y-m-d"))->sum('amt_usdt');
        $data['todayLifetimeReward']=$userDetail->lifetimeIncome()->where('created_at','>',date("Y-m-d"))->sum('amount');
        $data['todayDirectReward']=$userDetail->bonusReward()->where('created_at','>',date("Y-m-d"))->sum('amt_usdt');
        $data['todayIncome']=$data['todayStakingReward']+$data['todayStakingReferralReward']+$data['todayTeamDevelopmentReward']+$data['todayClubReward']+$data['todayDirectReward'];

        $data['AchievedClub']=(!is_null($userDetail->clubBusiness()['achieved'])?$userDetail->clubBusiness()['achieved']->clubname:'Not Achieved');
        $data['clubpowerline']=$userDetail->clubBusiness()['first'];
        $data['clubrestline']=$userDetail->clubBusiness()['rest'];

        $data['lifetimenextReward']=$userDetail->lifetimeAchievementBusiness()['next'];
        $data['lifetimepowerline']=$userDetail->lifetimeAchievementBusiness()['first'];
        $data['lifetimesecondline']=$userDetail->lifetimeAchievementBusiness()['second'];
        $data['lifetimerestline']=$userDetail->lifetimeAchievementBusiness()['rest'];

        return $this->sendResponse($data,'Today Data');
    }

    public function productWalletData(Request $request){
        $userDetail=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->first();

        $productAmount=\App\StackingDeposite::where('staketype',0)->where('userid',$userDetail->id)->sum('usdt');

        $images = [
            'https://cyera.ai/main/cai-diamond.jpg', 
            'https://cyera.ai/main/cai-bag.jpg',
            'https://cyera.ai/main/cai-fold.jpg',
            'https://cyera.ai/main/cai-watch.jpg'
        ];

        $data['productAmount']=$productAmount;
        $data['productImages']=$images;

        return $this->sendResponse($data,'Product Wallet Data');
    }

    public function userProfile(Request $request){//dd($request->user()->maskedEmail(),$request->user()->maskedContact());
        $userdata=$this->userDatas($request);
        return $this->sendResponse($userdata,'Profile Data');
    }

    public function userDatas($request){
        $editstatus=array();
        $editstatus=\App\AssetDetailChanges::where([['userid',$request->user()->userDetails()->first()->id],['asset_status','>',0]])->first();
        if(!is_null($editstatus)){
            $userdata['isEdited']=1;
        }else{
            $userdata['isEdited']=0;
        }
        $userdata['email']=$request->user()->maskedEmail();
        $userdata['name']=$request->user()->usersname;
        $userdata['userid']=$request->user()->uuid;
        $userdata['phone']=$request->user()->contact;
        $userdata['guiderId']=$request->user()->userDetails()->first()->guiderDetails()->first()->user()->uuid;
        $userdata['guiderName']=$request->user()->userDetails()->first()->guiderDetails()->first()->user()->usersname;
        $userdata['trcUsdtAddress']=(is_null($request->user()->userDetails()->first()->assetDetail())?null:$request->user()->userDetails()->first()->assetDetail()->usdttrc20addr);
        $userdata['bep20UsdtAddress']=(is_null($request->user()->userDetails()->first()->assetDetail())?null:$request->user()->userDetails()->first()->assetDetail()->usdtbep20addr);
        return $userdata;
    }

     public function updateUserDetails(Request $request){

        set_time_limit(0);
        if(isset($request->otp)){
            $vali=Validator::make($request->all(),[
                'otp'  =>['required','numeric'],
            ]);
            if($vali->fails()){
                return $this->sendError('Validation Error.', $vali->errors());       
            }
            $assetDetailChanges=\App\AssetDetailChanges::where([['userid',$request->user()->userDetails()->first()->id]])->first();
            if(is_null($assetDetailChanges)){
                return $this->sendError('Oops There is an error .Please try again later.', []);  
            }
            if($request->otp==$assetDetailChanges->token){
                $updateUser=\App\AssetDetail::firstOrCreate(['userid'=>$assetDetailChanges->userid]);
                if(!is_null($assetDetailChanges->bep20addr))
                $updateUser->bep20addr=$assetDetailChanges->bep20addr;
                if(!is_null($assetDetailChanges->usdttrc20addr))
                $updateUser->usdttrc20addr=$assetDetailChanges->usdttrc20addr;
                if(!is_null($assetDetailChanges->usdtbep20addr))
                $updateUser->usdtbep20addr=$assetDetailChanges->usdtbep20addr;
                $updateUser->updated_at=now();
                $updateUser->save();
                $deleteUser=\App\AssetDetailChanges::where('id',$assetDetailChanges->id)->delete();
                return $this->sendResponse($this->userDatas($request) ,'Profile Updated');
            }else{
                return $this->sendError('Invalid OTP', []);
            }
        }
        /*$editstatus=\App\AssetDetailChanges::where([['userid',$request->user()->userDetails()->first()->id],['asset_status','>',0]])->get();
        if(count($editstatus)){
            return $this->sendError('Validation Error.', ['errors'=>'You already have a mail in your inbox to confirm.Please check your inbox.']);
        }*/
        $vali=Validator::make($request->all(),[
            'trcUsdtAddress'   =>['string','nullable','regex:/^T[a-zA-Z0-9]{33}$/u'],
            'bep20UsdtAddress'  =>  ['string','nullable','regex:/^0x[a-fA-F0-9]{40}$/u'],
        ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors());       
        }
        $arrayName= array();
        if(!is_null($request->trcUsdtAddress)){$arrayName['usdttrc20addr'] = $request->trcUsdtAddress;}
        if(!is_null($request->bep20UsdtAddress)){$arrayName['usdtbep20addr'] = $request->bep20UsdtAddress;}
        if(sizeof($arrayName)){
            $arrayName['userid']=$request->user()->userDetails()->first()->id;
            $arrayName['token']=rand(100000,999999);
            $arrayName['email']=$request->user()->email;
            $arrayName['email_time']=now();
            $userDetailSave=\App\AssetDetailChanges::insertGetId($arrayName);
            $arrayName['view']="otpMail";
            $arrayName['subject']="OTP to confirm profile changes";
            $arrayName['useruuid']=$request->user()->uuid;
            $mail=new SupportQueryController();
            $response=$mail->sendMailgun($arrayName);
            if($response==0)
                $status=1;
            else
                $status=2;
            $updstatus=\App\AssetDetailChanges::where('id',$userDetailSave)->update(['asset_status'=>$status,'updated_at'=>now()]);
            return $this->sendResponse($this->userDatas($request) ,'We have sent an Email containing OTP to registered email id which required to confirm changes are made by you. Please enter OTP.');

        }else{
            return $this->sendError('Validation Error.', ['errors'=> 'please update atleast one address.']);
        }        
    }


    public function updateUserPasswords(Request $request){
        $vali=Validator::make($request->all(),[
            'old_password'=>    ['required', 'string'],
            'new_password'=>    ['required' ,'string', 'min:6'/*, 'confirmed'*/],
        ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors());
        }
        if(\Hash::check($request->old_password, $request->user()->password)){
            $userUpdate=\App\User::where('id',$request->user()->id)->update([
                'password'  => \Hash::make($request->new_password),
                's_password' => Crypt::encrypt($request->new_password),
            ]);

            $details['oldpassword']=$request->old_password;
            $details['newpassword']=$request->new_password;
            $details['userid']=$request->user()->uuid;

            return $this->sendResponse([],'Password Changed.');
        }else{
            return $this->sendError('Validation Error', ['errors'   => 'Old Password is not correct.']);
        }
    }

    public function directTeam(Request $request){
        $directList=DB::table('user_details')->where([['user_details.id',$request->user()->userDetails()->first()->id],['u.permission','1']])
        ->join('user_details as ud','user_details.userid','=','ud.sponsorid')
        ->join('users as u','ud.userid','=','u.id')
        ->select('u.usersname as name', 'u.uuid as userid', 'u.email as username', 'u.doj as doj', 'ud.current_self_investment as currentselfamount','ud.total_investment as teamtotal','ud.total_downline as totaldownline')
        ->selectRaw('case when ud.userstatus=0 then "Unpaid" when ud.userstatus=1 then "Paid" end as status')
        ->selectRaw('case when ud.userstatus=0 then "status-cancelled" when ud.userstatus=1 then "status-complete" end as statusclass')
        ->orderByRaw('u.id DESC')
        ->get();
        /*dd($directList);*/
        return $this->sendResponse($directList,'Direct Details');
    }

    public function getTotalTeam(Request $request){
        $ar=array();
        $usrid=DB::table('user_details')->where('id',$request->user()->userDetails()->first()->id)->get()->pluck('userid')->first();
        $udata=DB::table('users')->where('id',$usrid)->first();
        if(is_null($udata)){
            return $this->sendError('User not found.', []);
        }
        else{
            $uid=array($usrid);
            for($i=1;$i<21;$i++){
                $arl=array();
                $rData=DB::table('users')->where('users.permission','1')
                ->whereIn('ud.sponsorid',$uid)
                ->join('user_details as ud','users.id','=','ud.userid')
                ->join('users as gu','ud.sponsorid','=','gu.id')
                ->select(/*'users.id as id',*/'users.usersname as name','users.uuid as username','users.email as email','ud.total_investment as team_business',/*'users.uuid as userid',*/'ud.current_self_investment as self_investment'/*,'gu.usersname as guidername','gu.user_gf as guidergf'*/)
                ->selectRaw('"Level-'. $i.'" as level,DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
                ->selectRaw('case when ud.userstatus=0 then "Unpaid" when ud.userstatus=1 then "Paid" end as status')
                ->get();
                foreach ($rData as $key ) {
                    $key->email=$this->maskedEmail($key->email);
                    array_push($arl, $key);
                }
                $ar['level'.$i]=$arl;
                $id=DB::table('user_details')->whereIn('sponsorid',$uid)->selectRaw('userid')->get()->pluck('userid');
                if(count($id)==0||$id=="")
                    break;
                else{  
                    $uid=$id;
                }
            }

            return $this->sendResponse($ar,'Team Details');
        }
    }

    public function getTotalTeamHeading(Request $request){
        $data['totalbusiness']=\App\UserDetails::where('id',$request->user()->userDetails()->first()->id)->select('current_investment as totalbusiness')->first();
        $data['headingsmall']='Refer your friends';
        $data['headinglarge']='Earn upto 5% reward';

        return $this->sendResponse($data,'Team Dashboard');
    }

    public function userDirectList(Request $request){
        $datareg=$this->findUserName($request->userid);
        $regex=['required','exists:users,'.$datareg['type']];

        $direct=\App\UserDetails::where('u.'.$datareg['type'],$request->userid)->join('users as u','user_details.userid','=','u.id')->join('user_details as ud','ud.sponsorid','=','user_details.userid')->join('users','ud.userid','=','users.id')
        ->select('users.email as email','users.usersname as name','users.uuid as userid','users.contact as phone','users.doj as date','ud.current_self_investment as currentselfamount','ud.total_investment as currentteamamount','ud.total_downline as totaldownline','ud.leveluser as leveluser')->get();
        foreach($direct as $d){
            $d->email=$this->maskedEmail($d->email);
            $d->phone=$this->maskedContact($d->phone);
        };
        return $this->sendResponse($direct,'Direct Details');
    }




    public function arbitrageUrl(Request $request){

        $data['url']=URL::to('/arbitragedash');
        return $this->sendResponse($data,'Arbitrage Web View');
    }


    public function logOutUser(Request $request){
        auth('api')->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return $this->sendResponse([],'Logged Out.');
    }




}
