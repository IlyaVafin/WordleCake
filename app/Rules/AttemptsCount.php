<?php

namespace App\Rules;

use App\Models\Game;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Translation\PotentiallyTranslatedString;

class AttemptsCount implements ValidationRule
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $level = null;
        $gameId = $this->request->route('id');
        if ($this->request->input('level')) {
            $level = $this->request->input('level');
        } else if ($gameId) {
            $game = Game::findOrFail($gameId);
            $level = $game->level;
        }


        $attempts = (int) $value;
        if ($level == "easy" && $attempts < 12) {
            $fail("For the easy level there must be at least 12 attempts.");
        } else if ($level == "medium" && ($attempts < 8 || $attempts > 12)) {
            $fail("For the medium level must be at least 8 attempts and no more 12 attempts.");
        } else if ($level == "hard" && ($attempts < 1 || $attempts > 7)) {
            $fail("For the hard level must be at least 1 attempt and no more 7 attempts.");
        }
    }
}
