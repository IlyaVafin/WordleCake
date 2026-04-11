<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            "word" => "required|string"
        ]);
        $tokenCookie = $request->cookie("access_token");
        $token = PersonalAccessToken::findToken($tokenCookie);
        $user = User::where("id", $token->tokenable->id)->first();
        $gameSessionId = $request->route("game_uuid");
        $countAttempts = Attempt::where("session_id", $gameSessionId)->count();
        $session = GameSession::where("id", $gameSessionId)->first();
        if ($session->user_id != $user->id) {
            return response()->json(["message" => "Forbidden"], 403);
        }
        $game = Game::where("id", $session->game_id)->first();
        $session->attempts_left = $session->attempts_left - 1;

        if ($session->status != "open") {
            return response()->json(["message" => "Game is over"], 200);
        }

        if (
            $session->attempts_left == 0 &&
            strtolower($session->win_word) != strtolower($validated['word'])
        ) {
            $session->status = "lose";
            $session->end = now()->toDateTimeString();
            $user->points = max(0, $user->points - $game->decrement_points);
            $session->save();
            $user->save();
            return response()->json([
                "message" => "You lose :("
            ], 200);
        }

        if ($session->win_word == $validated['word']) {

            $attempt = Attempt::create([
                "number" => $countAttempts + 1,
                "word" => $validated["word"],
                "session_id" => $gameSessionId
            ]);

            $session->status = "win";
            $session->end = now()->toDateTimeString();
            $user->points = $user->points + $game->increment_points;
            $session->save();
            $user->save();
            return response()->json(["message" => "You win!"], 200);
        }

        $attempt = Attempt::create([
            "number" => $countAttempts + 1,
            "word" => $validated["word"],
            "session_id" => $gameSessionId
        ]);

        $session->save();
        $correctLetters = [];
        $word = $validated['word'];
        $wordChars = mb_str_split(mb_strtolower($word));
        $winWordChars = mb_str_split(mb_strtolower($session->win_word));

        foreach ($wordChars as $i => $char) {
            if (isset($winWordChars[$i]) && $winWordChars[$i] === $char) {
                $correctLetters[] = $i;
            }
        }


        return response()->json([
            "data" => [
                "user_attempts" => $session->attempts_left,
                "number" => $attempt->number,
                "word" => $attempt->word,
                "correct_letters" => $correctLetters
            ]
        ], 201);
    }
}
