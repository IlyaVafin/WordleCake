<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function store(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->cookie("access_token"));
        $user = $token->tokenable;
        $isCollected = Reward::where("user_id", $user->id)->whereDate('created_at', now()->today())->exists();
        if ($isCollected) return response()->json(["message" => "reward already claimed today"], 409);
        $reward = new Reward;
        $reward->user_id = $user->id;
        $reward->save();
        $user->points += 10;
        $user->save();
        return response(null, 204);
    }
}
