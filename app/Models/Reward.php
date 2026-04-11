<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    public $timestamps = false;
    protected $fillable = [
        "user_id"
    ];
}
