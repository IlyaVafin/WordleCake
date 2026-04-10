<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = [
        "number",
        "word",
        "session_id"
    ];
}
