<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AchievementIncome extends Model
{
    public $timestamps=false;

    protected $fillable =['userid','achievementid','amount','remaining','amt_usdt','remaining_usdt','txnDesc','status','intxna','intxnb','created_at','updated_at'];

    public function achievementDetail(){
        return $this->hasOne('\App\AchievementDetail','achievementid','id')->first();
    }

}
