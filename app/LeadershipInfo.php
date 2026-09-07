<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadershipInfo extends Model
{
    public $timestamps=false;

    protected $fillable=['userid','sponsorid','total_investment','total_level_investment','total_self_investment','total_direct_investment','leaderdate','updated_at','created_at','leadershipid'];
}
