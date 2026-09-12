<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaiMiningLedger extends Model
{
    protected $table = 'cai_mining_ledgers';

    protected $fillable = [
        'userid',
        'type',
        'usdt_amount',
        'cai_amount',
        'cai_price',
        'capping_before',
        'capping_deducted',
        'capping_after',
        'tx_hash',
        'status',
        'notes',
    ];

    public function userDetail()
    {
        return $this->belongsTo('App\UserDetails', 'userid', 'id');
    }
}
