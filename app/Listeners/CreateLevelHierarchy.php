<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateLevelHierarchy
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $userdetails=\App\UserDetails::where('userid',$event->arr['uid'])->first();
        $guiderid=$userdetails->sponsorid;
        $directupd=\App\UserDetails::where('userid',$guiderid);
        $directupd->increment('total_direct');
        $guiderid=$userdetails->sponsorid;
        while($guiderid>0){
            $guiderdetails=\App\UserDetails::where('userid',$guiderid)->first();
            if($guiderid>0 && !is_null($guiderdetails)){
                $teamupd=\App\UserDetails::where('userid',$guiderid);
                $teamupd->increment('total_downline');
                $guiderid=$guiderdetails->sponsorid;
            }
        }
    }
}
