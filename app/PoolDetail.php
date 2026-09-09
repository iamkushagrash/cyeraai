<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoolDetail extends Model
{
    protected $table = 'pool_details';

    protected $fillable = [
        'pool_name',
        'pool_type',
        'pool_percent',
        'min_self_investment',
        'min_directs',
        'min_direct_amount',
        'power_leg_amount',
        'weaker_leg_amount',
        'status',
    ];

    public function distributions()
    {
        return $this->hasMany('App\PoolDistribution', 'pool_id', 'id');
    }

    public function incomes()
    {
        return $this->hasMany('App\PoolIncome', 'pool_id', 'id');
    }
}
