<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LuckyDrawWinner extends Model
{
    protected $table = 'luckydraw_winners';

    protected $fillable = [
        'round_id',
        'pool_tier',
        'participant_id',
        'userid',
        'wallet_address',
        'ticket_number',
        'rank',
        'prize_amount',
        'credited_to_wallet',
    ];

    protected $casts = [
        'prize_amount' => 'float',
    ];

    public function round()
    {
        return $this->belongsTo('App\LuckyDrawRound', 'round_id', 'id');
    }

    public function participant()
    {
        return $this->belongsTo('App\LuckyDrawParticipant', 'participant_id', 'id');
    }

    public function userDetail()
    {
        return $this->belongsTo('App\UserDetails', 'userid', 'id');
    }
}
