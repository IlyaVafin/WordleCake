<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasUuids;
    protected $fillable = [
        "user_id",
        "game_id",
        "status",
        "attempts_left",
        "win_word",
        "start",
        "end"
    ];
}
