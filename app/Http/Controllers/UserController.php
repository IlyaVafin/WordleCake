<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy("points", "desc")->withCount(["sessions as count_games" => function ($query) {
            $query->whereIn("status", ["win", "lose"]);
        }])->limit(3)->get();
        $usersMapped = $users->map(function ($user, $i) {
            return  [
                "nickname" => $user->nickname,
                "email" => $user->email,
                "avatar" => $user->avatar,
                "count_games" => $user->count_games,
                "points" => $user->points,
                "top" => $i + 1
            ];
        });
        return response()->json([
            "data" => $usersMapped
        ]);
    }
}
