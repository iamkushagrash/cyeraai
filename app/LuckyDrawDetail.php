<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LuckyDrawDetail extends Model
{
    protected $table = 'luckydraw_details';

    protected $fillable = [
        'pool_tier',
        'pool_name',
        'ticket_price',
        'max_members',
        'min_staking_required',
        'prizes_json',
        'total_prize_pool',
        'current_round',
        'total_rounds_completed',
        'status',
    ];

    protected $casts = [
        'prizes_json' => 'array',
        'ticket_price' => 'float',
        'min_staking_required' => 'float',
        'total_prize_pool' => 'float',
    ];

    public function rounds()
    {
        return $this->hasMany('App\LuckyDrawRound', 'pool_tier', 'pool_tier');
    }

    public function activeRound()
    {
        return $this->hasOne('App\LuckyDrawRound', 'pool_tier', 'pool_tier')
            ->where('status', 0)
            ->orderBy('id', 'desc');
    }
}
