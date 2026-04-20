<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameSession extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        "user_id",
        "game_id",
        "status",
        "attempts_left",
        "win_word",
        "start",
        "end"
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, "game_id");
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class, "session_id");
    }
}
