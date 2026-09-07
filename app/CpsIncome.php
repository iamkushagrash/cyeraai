<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CpsIncome extends Model
{
    public $timestamps=false;

    protected $fillable=['userid','amount','remaining','amt_usdt','remaining_usdt','status','intxna','intxnb','created_at','updated_at','txnid','levelincome',];
}
