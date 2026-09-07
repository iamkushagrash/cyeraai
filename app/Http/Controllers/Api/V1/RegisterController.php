<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Mail;
use App\User;
use App\Mail\PasswordResetMail;
use App\Http\Controllers\Api\V1\BaseController;
use \App\Http\Controllers\SupportQueryController;
use Illuminate\Support\Facades\Crypt;

class RegisterController extends BaseController
{
    public function register(Request $request){
        $data=$request->all();

        $datareg=$this->findUserName($data['referrer']);
        $regex=['required',$datareg['regex']];

        if(!isset($data['contact']) || is_null($data['contact'])){
            $data['contact']='9999999999';
        }
        if(!isset($data['countrycode']) || is_null($data['countrycode'])){
            $data['countrycode']='91';
        }

        $vali=Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'/*, 'unique:users'*/],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referrer'  => ['required', 'string','regex:/^CAI|cai|Cai[0-9]{7}+$/']/*$regex*/,
            'contact'    => ['nullable', 'numeric'],
            'countrycode'    => ['nullable', 'string'],
            /*'gender'    => ['required', 'numeric'],*/
        ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors());
        }
        set_time_limit(0);
        $guiderid=0;
        if(!is_null($data['referrer'])){
            $guiderid= User::where('uuid',$data['referrer'])->select('id')->get()->first();
            if(is_null($guiderid))
                return $this->sendError('Invalid Referrer Id', $errorMessages = [], $code = 404);
        }

        \DB::beginTransaction();
        try{
            $uidd=$this->randomid();
            $user= User::create([
                'usersname' => $data['name'],
                'email' => $data['email'],
                'contact'    => $data['contact'],
                'ccode' => $data['countrycode'],
                'password' => Hash::make($data['password']),
                's_password' => Crypt::encrypt($data['password']),
                'doj'       => date("Y-m-d"),
                'created_at'  => now(),
                'uuid' => $uidd,
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
            $details['uniqueid']=$uidd;
            event(new \App\Events\UserRegistered($details));
            try{
                $details['subject']='Welcome to Cyera AI.';
                $details['view']='welcomeMail';
                $mailObj=new SupportQueryController();
                $mailStatus=$mailObj->sendMailgun($details);
                /*\Mail::to($data['email'])->send(new VerificationEmail($token));*/
                //\Mail::to($data['email'])->send(new WelcomeMail($details));
            }
            catch(Exception $e){
                \Log::info('Error in mails after registration of user id '.$userdetails);
                \Log::info($e->messages());
            }

            \DB::commit();
            /*$success['token'] =  $user->createToken('CAIApp')->accessToken;
            $success['name'] =  $user->name;*/
            $details=array('email' => $user->email, 'userid' => $uidd, 'password'=>$data['password'] ,);
            return $this->sendResponse($details, 'User register successfully.');
        }
        catch(Exception $e){
            \DB::rollback();
            \Log::info('Error for User '.$data['email']. ' Error message is '.$e->getMessage());
            return $this->sendError('You have some issue with registration. Please Try again.', $errorMessages = [], $code = 404);
        }

    }

    public function getSponsor(Request $request){
        $datareg=$this->findUserName($request->referrer);
        //$regex=['required',$datareg['regex']];
        $name=\App\User::where('uuid',$request->referrer)->pluck('usersname')->first();
        if(is_null($name))
            return $this->sendError('Invalid Referrer Id', $errorMessages = [], $code = 404);
        else{
            return $this->sendResponse($name, 'Sponser Name.');
        }
    }

    public function forgetPasswordMailSend(Request $request){
        $datareg=$this->findUserName($request->email);
        $regex=['required',$datareg['regex'],'exists:users,'.$datareg['type']];
        $vali=Validator::make($request->all(),[
            'userid'  => $regex,
        ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors()); 
        }
        $user=\App\User::where($datareg['type'],$request->userid)->first();
        if(!is_null($user)){
            $randomString=$this->generateRandomString(64);
            $delExisting=\DB::delete('delete from password_resets where email = ?', [$request->userid]);
            $insPswdReset=\DB::table('password_resets')->insert(['email'=>$request->userid,'token'=>\Hash::make($randomString),'created_at'=>now()]);
            $data['user']=$user->email;
            $data['token']=$randomString;
            /*Mail::to($user->email)->send(new PasswordResetMail($data));*/
            return $this->sendResponse([],'Password reset link successfully sent to your mail '.$user->email);
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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
