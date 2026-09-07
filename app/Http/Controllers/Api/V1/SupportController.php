<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Support\Facades\Validator;
use \App\Http\Controllers\SupportQueryController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use Mail;
use App\User;

class SupportController extends BaseController
{
    public function viewUserTicket(Request $request)
    {
        $data=$this->userTicket($request);
        return $this->sendResponse($data,'Support Page');
        //return $this->sendError('Validation Error.', $vali->errors());
    }

    public function userTicket($request)
    {
        $data['ticketList']=\App\SupportQuery::where([['support_queries.uid',$request->user()->userDetails()->first()->id],['inqid',0]])
        ->select('subject as sub','message as htext','title as title','support_queries.created_at as created_at','status as status')
        ->selectRaw('case when support_queries.status=0 then "New" when support_queries.status=1 then "Replied" when support_queries.status=2 then "Closed" end as status, (support_queries.id*'.pow($request->user()->userDetails()->first()->id, 3).') as ticket_id')
        /*->selectRaw('case when support_queries.status=0 then "status-cancelled" when support_queries.status=1 then "status-pending" when support_queries.status=2 then "status-complete" end as statusclass')*/
        ->get();
        return $data;
    }

    public function userCreateTicket(Request $request)
    {
        $vali=Validator::make($request->all(),[
            'subject' =>['required','string','regex:/^[^<>]+$/u'],
            'message'=>['required','string','regex:/^[^<>]+$/u'],
            'title'=>['required','string','regex:/^[^<>]+$/u'],
        ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors());
        }
        $insSupprt=\App\SupportQuery::create([
            'uid'=> $request->user()->userDetails()->first()->id,
            'subject'=> $request->subject,
            'title'=> $request->title,
            'message'=> $request->message,
            'status'=> 0,
            'created_at' => now(),
        ]);
        $data=$this->userTicket($request);
        return $this->sendResponse($data,'Query Submitted Successfully.');
    }

    public function viewTicketSingleUser(Request $request,$id)
    {
        $data=$this->singleTicket($request,$id);
        return $this->sendResponse($data,'Ticket Detail');
    }

    public function singleTicket($request,$id){
        $tid=($id/pow($request->user()->userDetails()->first()->id, 3));
        $data['ticket_detail']=\App\SupportQuery::where([['support_queries.uid',$request->user()->userDetails()->first()->id],['support_queries.inqid',$tid]])->orWhere('support_queries.id',$tid)
        ->join('user_details','support_queries.uid','=','user_details.id')
        ->select('subject as subject','message as htext','title as title','support_queries.repliedby as ustatus','support_queries.id as subid','support_queries.created_at as created_at')
        ->selectRaw('(support_queries.id*'.pow($request->user()->userDetails()->first()->id, 3).') as ticket_id')
        /*->selectRaw('case when support_queries.status=0 then "New" when support_queries.status=1 then "Replied" when support_queries.status=2 then "Closed"  end as status')*/
        ->get();
        return $data;
    }

    public function postReplyUser(Request $request){
        $vali=Validator::make($request->all(),[
            'ticket_id' =>['required','integer'],
            'textmsg'=>['required','string','regex:/^[^<>]+$/u'],
        ]);
        if($vali->fails()){
            return $this->sendError('Validation Error.', $vali->errors());
        }
        $tid=($request->ticket_id/pow($request->user()->userDetails()->first()->id, 3));
        $qry="insert into support_queries (uid,inqid,message,status,created_at) values (".$request->user()->userDetails()->first()->id.",".($tid).",'".$request->textmsg."',0,'".now()."')";
        $d=DB::statement($qry);
        $data=$this->singleTicket($request,$request->ticket_id);
        return $this->sendResponse($data,'Reply submitted successfully.');
    }


    public function getSponsorInside(Request $request){
        $datareg=$this->findUserName($request->referrer);
        //$regex=['required',$datareg['regex']];
        $name=\App\User::where('uuid',$request->referrer)->pluck('usersname')->first();
        if(is_null($name))
            return $this->sendError('Invalid Referrer Id', $errorMessages = [], $code = 404);
        else{
            return $this->sendResponse($name, 'Sponser Name.');
        }
    }

    public function userNewRegistration(Request $request){
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
            $guiderid = User::where('uuid',$data['referrer'])->select('id')->get()->first();
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






    public function appUpd(Request $request)
    {
        $data=\App\AppUpdate::where('status',1)->orderBy('id', 'desc')->first();
        return $this->sendResponse($data,'App Update');
    }

    
}
