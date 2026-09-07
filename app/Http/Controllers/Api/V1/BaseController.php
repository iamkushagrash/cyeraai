<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function sendResponsetoMetaWallet($result, $message)
    {
        $response = [
            /*'success' => true,*/
            'message'    => $result,
            /*'message' => $message,*/
        ];


        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }


    public function sendErrortoMetaWallet($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    /**
     * return masked Email.
     *
     * @return string
     */
    public function maskedEmail($value){
        $em   = explode("@",$value);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

    /**
     * return masked contact.
     *
     * @return string
     */
    public function maskedContact($value){
        $length=strlen($value);
        $frontlength=substr($value,$length-10);
        return  substr($value,0, $length-strlen($frontlength)).substr($value,$length-strlen($frontlength), 2) . str_repeat('*', 5) . substr($value,$length-3, $length);
    }


    public function findUserName($value){
        if(filter_var($value, FILTER_VALIDATE_EMAIL)){
            $data['type']='email';
            $data['regex']='email';
        }else{
            $data['type']='uuid';
            $data['regex']='regex:/^CAI|cai[0-9]{7}+$/';
        }
        return $data;
    }
}
