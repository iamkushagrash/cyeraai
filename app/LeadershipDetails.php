<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadershipDetails extends Model
{
    public $timestamps=false;

    protected $fillable=['name','investment','roi','isRepeating','status','updated_at',];
}
