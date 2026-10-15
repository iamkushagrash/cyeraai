<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoyalWalletLedger extends Model
{
    protected $table = 'royal_wallet_ledgers';

    protected $fillable = [
        'userid',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'round_id',
        'claim_id',
        'tx_hash',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'balance_before' => 'float',
        'balance_after' => 'float',
    ];

    public function userDetail()
    {
        return $this->belongsTo('App\UserDetails', 'userid', 'id');
    }

    public function round()
    {
        return $this->belongsTo('App\LuckyDrawRound', 'round_id', 'id');
    }
}
