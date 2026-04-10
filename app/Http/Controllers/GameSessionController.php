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
        $tokenCookie = $request->cookie("access_token");
        $token = PersonalAccessToken::findToken($tokenCookie);
        $user = $token->tokenable;
        $game = Game::where("id", $gameId);
        $ageLimit = $game->value("age_limit");
        $userAge = Carbon::parse($user->birthday)->diffInYears(now());

        if ($userAge < $ageLimit) {
            return response()->json(["message" => "age restriction"], 403);
        }

        $hasActiveSession = GameSession::where("user_id", $user->id)->where("status", "open")->exists();

        if ($hasActiveSession) {
            return response()->json(["message" => "Conflict"], 409);
        }

        $randomWord = Word::where("game_id", $gameId)->inRandomOrder()->first();

        $session = GameSession::create([
            "user_id" => $user->id,
            "game_id" => $gameId,
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
        if (!$session) return response()->json(["message" => "Not found"], 404);
        $game = Game::where("id", $session->game_id)->first();
        return response()->json([
            "data" => $session,
            "game" => $game
        ]);
    }
}
