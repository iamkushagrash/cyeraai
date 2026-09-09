<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RankIncome extends Model
{
    protected $table = 'rank_incomes';

    protected $fillable = [
        'userid',
        'rank_id',
        'rank_name',
        'amount',
        'remaining',
        'amt_usdt',
        'remaining_usdt',
        'power_leg_business',
        'weaker_leg_business',
        'distributed_date',
        'status'
    ];

    public function userDetail()
    {
        return $this->belongsTo('\App\UserDetails', 'userid', 'id');
    }

    public function rankDetail()
    {
        return $this->belongsTo('\App\RankDetail', 'rank_id', 'id');
    }
}
