<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoolDistribution extends Model
{
    protected $table = 'pool_distributions';

    protected $fillable = [
        'pool_id',
        'pool_type',
        'period_start',
        'period_end',
        'total_turnover',
        'pool_percent',
        'total_pool_amount',
        'eligible_users_count',
        'per_user_share',
        'distributed_date',
        'status',
    ];

    public function poolDetail()
    {
        return $this->belongsTo('App\PoolDetail', 'pool_id', 'id');
    }

    public function incomes()
    {
        return $this->hasMany('App\PoolIncome', 'distribution_id', 'id');
    }
}
