<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LuckyDrawParticipant extends Model
{
    protected $table = 'luckydraw_participants';

    protected $fillable = [
        'round_id',
        'pool_tier',
        'userid',
        'wallet_address',
        'ticket_number',
        'amount_paid',
        'tx_hash',
    ];

    protected $casts = [
        'amount_paid' => 'float',
    ];

    public function round()
    {
        return $this->belongsTo('App\LuckyDrawRound', 'round_id', 'id');
    }

    public function userDetail()
    {
        return $this->belongsTo('App\UserDetails', 'userid', 'id');
    }

    public function winnerRecord()
    {
        return $this->hasOne('App\LuckyDrawWinner', 'participant_id', 'id');
    }
}
