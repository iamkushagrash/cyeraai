<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoolIncome extends Model
{
    protected $table = 'pool_incomes';

    protected $fillable = [
        'distribution_id',
        'userid',
        'pool_id',
        'pool_type',
        'amount',
        'remaining',
        'amt_usdt',
        'remaining_usdt',
        'status',
    ];

    public function userDetail()
    {
        return $this->belongsTo('App\UserDetails', 'userid', 'id');
    }

    public function poolDetail()
    {
        return $this->belongsTo('App\PoolDetail', 'pool_id', 'id');
    }

    public function distribution()
    {
        return $this->belongsTo('App\PoolDistribution', 'distribution_id', 'id');
    }
}
