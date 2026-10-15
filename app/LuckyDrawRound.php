<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LuckyDrawRound extends Model
{
    protected $table = 'luckydraw_rounds';

    protected $fillable = [
        'pool_tier',
        'round_number',
        'max_members',
        'current_members',
        'ticket_price',
        'total_collected',
        'status',
        'started_at',
        'drawn_at',
    ];

    protected $casts = [
        'ticket_price' => 'float',
        'total_collected' => 'float',
        'started_at' => 'datetime',
        'drawn_at' => 'datetime',
    ];

    public function detail()
    {
        return $this->belongsTo('App\LuckyDrawDetail', 'pool_tier', 'pool_tier');
    }

    public function participants()
    {
        return $this->hasMany('App\LuckyDrawParticipant', 'round_id', 'id');
    }

    public function winners()
    {
        return $this->hasMany('App\LuckyDrawWinner', 'round_id', 'id')->orderBy('rank', 'asc');
    }
}
