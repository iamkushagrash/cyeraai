<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaoIncome extends Model
{
    protected $table = 'dao_incomes';

    protected $fillable = [
        'userid',
        'dao_type',
        'dao_name',
        'distribution_id',
        'amount',
        'remaining',
        'amt_usdt',
        'remaining_usdt',
        'status',
    ];

    public function distribution()
    {
        return $this->belongsTo('App\DaoDistribution', 'distribution_id', 'id');
    }

    public function userDetail()
    {
        return $this->belongsTo('App\UserDetails', 'userid', 'id');
    }
}
