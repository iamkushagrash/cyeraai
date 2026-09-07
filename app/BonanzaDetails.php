<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonanzaDetails extends Model
{
    public $timestamps=false;

    protected $fillable =[
        'userid',
        'sponsorid',
        'total_investment',
        'current_investment',
        'total_level_investment',
        'current_level_investment',
        'total_self_investment',
        'current_self_investment',
        'total_direct_investment',
        'current_direct_investment',
        'created_at',
        'updated_at',
        'first',
        'rest'
    ];
}
