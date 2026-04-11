<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\PersonalAccessToken;
use App\Models\Word;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GameSessionController extends Controller
{
    public function store(Request $request)
    {
        $gameId = $request->route("game_id");
        $game = Game::where("id", $gameId);
        if ($game->value("status") == "deleted") {
            return response()->json(["message" => "Game is deleted"]);
        }
        $tokenCookie = $request->cookie("access_token");
        $token = PersonalAccessToken::findToken($tokenCookie);
        $user = $token->tokenable;
        $ageLimit = $game->value("age_limit");
        $userAge = Carbon::parse($user->birthday)->diffInYears(now());

        if ($userAge < $ageLimit) {
            return response()->json(["message" => "age restriction"], 403);
        }

        if ($user->points - $game->value("decrement_points") < 0) {
            return response()->json(["message" => "insufficient points"], 409);
        }

        $hasActiveSession = GameSession::where("user_id", $user->id)->where("status", "open")->exists();

        if ($hasActiveSession) {
            return response()->json(["message" => "Conflict"], 409);
        }

        $randomWord = Word::where("game_id", $gameId)->inRandomOrder()->first();

        $session = GameSession::create([
            "user_id" => $user->id,
            "game_id" => intval($gameId),
            "status" => "open",
            "attempts_left" => $game->value("attempts"),
            "win_word" => $randomWord->text,
            "start" => now()
        ]);
        return response()->json([
            "session" => $session
        ], 201);
    }

    public function show(Request $request)
    {
        $gameSessionId = $request->route("game_uuid");
        $session = GameSession::where("id", $gameSessionId)->first();
        $game = Game::where("id", $session->game_id)->first();
        $categoryName = $game->category->value("name");
        if (!$session) return response()->json(["message" => "Not found"], 404);
        $attempts = $session->attempts()->get();
        return response()->json([
            "data" => [
                "uuid" => $session->id,
                "category" => $categoryName,
                "level" => $game->level,
                "increment_points" => $game->increment_points,
                "decrement_points" => $game->decrement_points,
                "attempts" => $game->attempts,
                "preview" => $game->preview,
                "age_limit" => $game->age_limit,
                "status" => $session->status,
                "start" => $session->start,
                "end" => $session->end,
                "win_word" => $session->status == "open" ? null : $session->win_word,
                "user_attempts" => $session->attempts_left,
                "user_attempts" => $attempts
            ],
        ]);
    }
}
