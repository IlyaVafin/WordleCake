<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->cookie("access_token"));
        $user = $token->tokenable;
        $sessions = GameSession::where("user_id", $user->id)->with(["game.category"])->withCount("attempts")->get();
        $mappedSessions = $sessions->map(function ($session) {
            $object = [
                "category" => $session->game->category->name,
                "level" => $session->game->level,
                "status" => $session->status,
                "increment_points" => $session->game->increment_points,
                "decrement_points" => $session->game->decrement_points,
                "attempts" => $session->attempts_count,
                "user_attempts" => $session->game->attempts - $session->game->attempts_count,
                "preview" => $session->game->preview,
                "age_limit" => $session->game->age_limit,
                "start" => $session->start,
                "end" => $session->end
            ];
            if ($session->status != "open") {
                $object['win_word'] = $session->win_word;
            }
            return $object;
        });
        $top = User::where("points", ">", $user->points)->count() + 1;
        $totalGame = $sessions->count();
        $user->makeHidden("id");
        $user->makeHidden("superuser");
        return response()->json([
            "data" => [
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "nickname" => $user->nickname,
                "birthday" => $user->birthday,
                "email" => $user->email,
                "avatar" => $user->avatar,
                "count_games" => $totalGame,
                "points" => $user->points,
                "top" => $top,
                "games" => $mappedSessions
            ]
        ]);
    }

    public function update(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->cookie("access_token"));
        $user = $token->tokenable;
        $validated = $request->validate([
            "nickname" => "sometimes|string|min:1",
            "first_name" => "sometimes|string",
            "last_name" => "sometimes|string",
            "avatar" => "sometimes|mimes:png,jpg,jpeg|max:2048"
        ]);
        $user->update($validated);
        if ($request->hasFile("avatar")) {
            $user->avatar = Storage::disk("public")->putFile("avatar", $request->file("avatar"));
            $user->save();
        }
        return response(null, 204);
    }
}
