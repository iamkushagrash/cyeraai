<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\UserDetails;
use App\User;
use App\AssetDetail;
use DB;
use Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Mail\WelcomeMail;

class AssetDetailController extends Controller
{
    //Admin
    public function userDirectPage(){
        return view('control.userdirect')->with('data',array());
    }
    public function userDirectTeamList(Request $request){
        $datareg=$this->findUserName($request->userrid);
        $diveid=DB::table('users')->where($datareg['type'],$request->userrid)
        ->join('user_details','user_details.userid','=','users.id')
        ->select('user_details.id as userid')->first();
        if(is_null($diveid)){
            return redirect()->back()->with('warning','User Not Found');
        }
        $directList=DB::table('user_details')->where('user_details.id',$diveid->userid)
        ->join('user_details as ud','user_details.userid','=','ud.sponsorid')
        ->join('users as u','ud.userid','=','u.id')
        ->select('u.id as id', 'u.usersname as name', 'u.email as email', 'u.uuid as userid', 'u.doj as doj', 'ud.current_self_investment as shares','ud.total_investment as teamtotal')
        ->selectRaw('case when ud.userstatus=0 then "Inactive" when ud.userstatus=1 then "Active" end as status')
        ->selectRaw('case when ud.userstatus=0 then "status-cancelled" when ud.userstatus=1 then "status-complete" end as statusclass')
        ->orderByRaw('u.id DESC')
        ->get();
        return view('control.userdirect')->with('data',$directList);
    }

    public function userAllTeamPage(){
        return view('control.userall')->with('data',array());
    }
    public function userAllTeamList(Request $request){
        $datareg=$this->findUserName($request->userrid);

        $ar=array();
        $usrid=DB::table('user_details')->where($datareg['type'],$request->userrid)->join('users','users.id','=','user_details.userid')
        ->get()->pluck('userid')->first();

        if(is_null($usrid)){
            return redirect()->back()->with('warning','User Not Found');
        }
        
        $udata=DB::table('users')->where('id',$usrid)->get()->first();
        if(is_null($udata)){
            return view('control.userall')->with('Warning','This User Does Not Exists.');
        }
        else{
                $uid=array($usrid);
                for($i=1;$i<1000;$i++){
                    
                        $rData=\App\User::whereIn('ud.sponsorid',$uid)
                        ->join('user_details as ud','users.id','=','ud.userid')
                        ->join('users as gu','ud.sponsorid','=','gu.id')
                        ->select('users.id as id','users.usersname as name','users.email as email','users.uuid as userid','ud.current_self_investment as current','ud.leveluser as leveluser', 'users.permission as permission')
                        ->selectRaw('"Level-'. $i.'" as level,DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
                        ->selectRaw('case when ud.userstatus=0 then "Inactive" when ud.userstatus=1 then "Active" end as status')
                        ->selectRaw('case when ud.userstatus=0 then "status-cancelled" when ud.userstatus=1 then "status-complete" end as statusclass')
                        ->get();
                        foreach ($rData as $key ) {
                           array_push($ar, $key);
                        }
                        $id=DB::table('user_details')->whereIn('sponsorid',$uid)->selectRaw('userid')->get()->pluck('userid');
                        if(count($id)==0||$id=="")
                            break;
                        else{  
                            $uid=$id;
                        }

                }
                return view('control.userall')->with('data',$ar);
            } 
    }

    public function userAllTeamAjax(Request $request){
        $datareg=$this->findUserName($request->userrid);

        $ar=array();
        $usrid=DB::table('user_details')->where($datareg['type'],$request->userrid)->join('users','users.id','=','user_details.userid')
        ->get()->pluck('userid')->first();

        if (!$usrid) {
            return response()->json([
                'status' => false,
                'msg' => 'User Not Found'
            ]);
        }

        $all = [];
        $uids = [$usrid];

        for ($i = 1; $i <= 1000; $i++) {

            $rows = User::join('user_details as ud', 'users.id', '=', 'ud.userid')
                ->whereIn('ud.sponsorid', $uids)
                ->select(
                    'users.usersname',
                    'users.email',
                    'users.uuid as userid',
                    'users.permission',
                    'ud.current_self_investment as shares',
                    'ud.leveluser',
                    'ud.userstatus',
                    DB::raw('DATE_FORMAT(users.doj,"%d-%m-%Y") as doj')
                )
                ->selectRaw("'Level-$i' as level")
                ->get();

        if ($rows->isEmpty()) break;

            foreach ($rows as $r) {
                $all[] = $r;
            }

            $uids = DB::table('user_details')
                ->whereIn('sponsorid', $uids)
                ->pluck('userid')
                ->toArray();
        }

        return response()->json([
            'status' => true,
            'data' => $all
        ]);
    }

    //User Referral & Invite Page
    public function userReferralPage(){
        $user = \App\UserDetails::where('id', \Session::get('user.id'))->first();
        $totalDirects = \App\UserDetails::where('sponsorid', \Session::get('user.uuid'))->count();
        $activeDirects = \App\UserDetails::where('sponsorid', \Session::get('user.uuid'))->where('userstatus', 1)->count();
        
        $asset = \App\AssetDetail::where('userid', \Session::get('user.id'))->first();
        $userWallet = '';
        if ($asset && !empty($asset->usdtbep20addr)) {
            $userWallet = $asset->usdtbep20addr;
        } elseif ($asset && !empty($asset->bep20addr)) {
            $userWallet = $asset->bep20addr;
        } else {
            $userWallet = \Session::get('user.walletaddress') ?? \Session::get('user.uuid', 'CYERA');
        }

        return view('user.referral')
            ->with('user', $user)
            ->with('totalDirects', $totalDirects)
            ->with('activeDirects', $activeDirects)
            ->with('userWallet', $userWallet);
    }

    public function userNewRegistrationPage(){
        $details=NULL;
        $asset = \App\AssetDetail::where('userid', \Session::get('user.id'))->first();
        $myWallet = ($asset && !empty($asset->usdtbep20addr)) ? $asset->usdtbep20addr : (\Session::get('user.walletaddress') ?? \Session::get('user.uuid'));
        return view('user.newregistration')->with('details',$details)->with('myWallet', $myWallet);
    }

    public function userNewRegistration(Request $request)
    {   
        set_time_limit(0);
        
        Validator::make($request->all(), [
            'name'        => ['nullable', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'referrer'    => ['required', 'string'],
            'contact'     => ['required', 'numeric'],
            'countrycode' => ['nullable', 'string'],
        ])->validate();

        $guiderid = 0;
        if (!empty($request->referrer)) {
            $guiderUser = \App\Http\Controllers\Auth\RegisterController::resolveSponsorUser($request->referrer);
            if (is_null($guiderUser)) {
                return redirect()->back()->with('warning', 'Referrer Sponsor Wallet Address / ID incorrect. Please verify and try again.');
            }
            $guiderDetail = UserDetails::where('userid', $guiderUser->id)->first();
            $guiderid = $guiderDetail ? $guiderDetail->id : $guiderUser->id;
        }

        $randomId = $this->randomid();
        $displayName = !empty($request->name) ? $request->name : ('Member_' . substr($randomId, 3));
        
        $user = User::create([
            'usersname'         => $displayName,
            'email'             => $request->email,
            'contact'           => $request->contact,
            'ccode'             => $request->countrycode,
            'password'          => Hash::make($request->password),
            's_password'        => Crypt::encrypt($request->password),
            'doj'               => date("Y-m-d"),
            'created_at'        => now(),
            'uuid'              => $randomId,
            'email_verified_at' => now(),
        ]);

        $userdetails = \App\UserDetails::insertGetId([
            'userid'    => $user->id,
            'sponsorid' => is_object($guiderid) ? $guiderid->id : $guiderid
        ]);

        $details['id'] = $user->email;
        $details['password'] = $request->password;
        $details['uid'] = $userdetails;
        $details['email'] = $request->email;
        $details['contact'] = $request->contact;
        $details['name'] = $displayName;
        $details['referrerid'] = $request->referrer;
        $details['uniqueid'] = $randomId;
        $details['view'] = 'welcomeMail';
        $details['subject'] = 'Welcome to Cyera AI.';
        
        event(new \App\Events\UserRegistered($details));
        try {
            $mailObj = new SupportQueryController();
            $mailStatus = $mailObj->sendMailgun($details);
        } catch(\Exception $e) {
            \Log::info('Error in mails after registration: ' . $e->getMessage());
        }

        return redirect()->back()->with('details', $details)->with('success', 'Registration Successful.');
    }

    public function randomid(){
        $val=true;
        while ( $val) {
            $num="CAI".rand(1111111,9999999);
            $chkUser=\App\User::where('uuid',$num)->first();
            if(is_null($chkUser)){
                $val=false;
            }
        }
        return $num;
    }

    public function getSponsor($id){
        $id = trim($id);
        $user = \App\Http\Controllers\Auth\RegisterController::resolveSponsorUser($id);
        if (is_null($user)) {
            return response()->json(['status' => 1, 'message' => 'Invalid Sponsor']);
        }
        
        $sponsorDetail = UserDetails::where('userid', $user->id)->first();
        $sponsorWallet = '';
        if ($sponsorDetail) {
            $asset = AssetDetail::where('userid', $sponsorDetail->id)->first();
            if ($asset && !empty($asset->usdtbep20addr)) {
                $sponsorWallet = $asset->usdtbep20addr;
            } elseif ($asset && !empty($asset->bep20addr)) {
                $sponsorWallet = $asset->bep20addr;
            }
        }

        $displayTag = !empty($sponsorWallet) 
            ? (substr($sponsorWallet, 0, 6) . '...' . substr($sponsorWallet, -4)) 
            : $user->uuid;

        return response()->json([
            'status'  => 0,
            'name'    => $displayTag,
            'display' => $displayTag,
            'wallet'  => $sponsorWallet,
            'uuid'    => $user->uuid
        ]);
    }
}
