<?php

namespace App\Http\Controllers;

use App\Enums\GameLevel;
use App\Models\Category;
use App\Models\Game;
use App\Models\Word;
use App\Rules\AttemptsCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            "category_id" => "required|integer",
            "words" => "required|array|min:10"
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

        foreach ($validated['words'] as $word) {
            Word::create([
                "text" => $word,
                "game_id" => $game->id
            ]);
        }
        $words = Word::all()->where("game_id", $game->id);
        return response()->json([
            "game" => $game,
            "words" => $words
        ], 201);
    }


    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            "level" => ["sometimes", new Enum(GameLevel::class)],
            "increment_points" => "sometimes|integer|min:0",
            "decrement_points" => "sometimes|integer|min:0|lte:increment_points",
            "age_limit" => "sometimes|integer|min:0|max:21",
            "attempts" => ["sometimes", "integer", new AttemptsCount($request)],
            "category_id" => "sometimes|integer",
            "status" => "somteimes|in:active,deleted"
        ]);

        Log::info($validated);

        $game = Game::find($id);

        $game->update($validated);
        return response()->json([
            "game" => $game
        ]);
    }

    public function destroy(string $id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
        return response()->json(null, 204);
    }
}
