<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
/*use App\Mail\VerificationEmail;
use App\Mail\WelcomeMail;*/
use Mail;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $info=$this->findUserName($data['referrer']);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'/*, 'unique:users'*/],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referrer'  =>['required',$info['regex']],
            'contact'    => ['required', 'numeric'],
            'countrycode'    => ['nullable', 'string'],
            /*'gender'    => ['required', 'numeric'],*/
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        set_time_limit(0);
        $guiderid=0;
        if(!is_null($data['referrer'])){
            $info=$this->findUserName($data['referrer']);
            $guiderid=User::where('uuid',$data['referrer'])->select('id')->get()->first();
            if(is_null($guiderid)){
                return redirect()->back()->with('warning','Referrer User Id incorrect.Register with correct Referrer User Id.');
            }
        }
        
        $randomId=$this->randomid();
        $user= User::create([
            'usersname' => $data['name'],
            'email' => $data['email'],
            'contact'    => $data['contact'],
            'ccode' => $data['countrycode'],
            /*'gender'    => $data['gender'],*/
            'password' => Hash::make($data['password']),
            's_password'    =>  Crypt::encrypt($data['password']),
            'doj'       => date("Y-m-d"),
            'created_at'  => now(),
            'uuid' => $randomId,
            'email_verified_at' => now(),
            ]);
        $userdetails=\App\UserDetails::insertGetId([
                'userid'   => $user->id,
                'sponsorid' => is_object($guiderid)?$guiderid->id:$guiderid
            ]);
        $token = Str::random(64);
        /*$userVerification=\App\UserVerification::create([
            'userid'  =>  $user->id,
            'token'  =>  $token,
            'purpose'  =>  'verification',
            'created_at'  =>  now(),
        ]);*/
        
        $details['id']=$user->email;
        $details['password']=$data['password'];
        $details['uid']=$userdetails;
        $details['email']=$data['email'];
        $details['contact']=$data['contact'];
        $details['name']=$data['name'];
        $details['referrerid']=$data['referrer'];
        $details['email']=$data['email'];
        $details['uniqueid']=$randomId;
        event(new \App\Events\UserRegistered($details));
        try{
            $details['subject']='Welcome to Cyera AI.';
            $details['view']='welcomeMail';
            $mailObj=new SupportQueryController();
            $mailStatus=$mailObj->sendMailgun($details);
            //\Mail::to($data['email'])->send(new VerificationEmail($token));
            //\Mail::to($data['email'])->send(new WelcomeMail($details));
        }
        catch(Exception $e){
            \Log::info('Error in mails after registration of user id '.$userdetails);
            \Log::info($e->messages());
        }

        $details=array('username' => $user->email, 'password'=>$data['password'] ,'uniqueid'=>$randomId);
        return redirect('/register')->with('details',$details)->with('success','Registration Successful.');
    }


    public function getSponsor($id){
        $data=array();
        $info=$this->findUserName($id);
        $name=\App\User::where($info['type'],$id)->pluck('usersname')->first();
        if(is_null($name))
            $data["status"]=1;
        else{
            $data["status"]=0;
            $data["name"]=$name;
        }
        return $data;
    }

    public function reffer($userid){
        return view('auth.register')->with('userid',$userid);
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
}
