<?php

namespace App\Http\Controllers;

use App\Enums\GameLevel;
use App\Models\Category;
use App\Models\Game;
use App\Rules\AttemptsCount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class GameController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            "level" => ["required", new Enum(GameLevel::class)],
            "increment_points" => "required|integer|min:0",
            "decrement_points" => "required|integer|min:0|lte:increment_points",
            "age_limit" => "required|integer|min:0|max:21",
            "attempts" => ["required", "integer", new AttemptsCount($request)],
            "category_id" => "required|integer"
        ]);

        $category = Category::findOrFail($validated["category_id"]);
        
        $game = $category->games()->create([
            "level" => $validated["level"],
            "increment_points" => floatval($validated["increment_points"]),
            "decrement_points" => floatval($validated["decrement_points"]),
            "age_limit" => intval($validated["age_limit"]),
            "preview" => $category->image,
            "attempts" => intval($validated["attempts"]),

        ]);

        return response()->json([
            "game" => $game
        ], 201);
    }
}
