<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function show()
    {
        return response()->json([
            'status' => true,
            'data'   => Notification::orderBy('created_at', 'desc')->get()
        ]);
    }

   
}
