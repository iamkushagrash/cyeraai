<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RankDetail extends Model
{
    protected $table = 'rank_details';

    protected $fillable = [
        'rank_name',
        'rank_level',
        'power_leg',
        'weaker_leg',
        'weekly_reward',
        'status'
    ];

    public function rankIncomes()
    {
        return $this->hasMany('\App\RankIncome', 'rank_id', 'id');
    }
}
