<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppUpdate extends Model
{
    public $timestamps=false;

    protected $fillable =['androidversion','androidurl','status'];
}
