<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaoDistribution extends Model
{
    protected $table = 'dao_distributions';

    protected $fillable = [
        'dao_type',
        'dao_name',
        'week_start',
        'week_end',
        'total_weekly_business',
        'pool_percent',
        'pool_amount',
        'qualified_members',
        'per_member_payout',
        'status',
    ];

    public function incomes()
    {
        return $this->hasMany('App\DaoIncome', 'distribution_id', 'id');
    }
}
