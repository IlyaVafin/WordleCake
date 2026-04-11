<?php

namespace App\Http\Controllers;

use App\Enums\GameLevel;
use App\Models\Category;
use App\Models\Game;
use App\Models\Word;
use App\Rules\AttemptsCount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::query();
        $queryName = $request->query("category");
        $queryMinVictoryPoints = $request->query("min_increment_points");
        $queryMaxVictoryPoints = $request->query("max_increment_points");
        $queryMinDecrementPoints = $request->query("min_decrement_points");
        $queryMaxDecrementPoints = $request->query("max_decrement_points");
        $querySortByAttempts = $request->query("sort_by_attempts");
        $queryLevel = $request->query("level");

        if ($queryName) {
            $query->whereHas("category", function ($q) use ($queryName) {
                $q->where('name', 'like', '%' . $queryName . '%');
            });
        }
        if ($queryMinVictoryPoints) {
            $query->where("increment_points", ">=", $queryMinVictoryPoints);
        }
        if ($queryMaxVictoryPoints) {
            $query->where("increment_points", "<=", $queryMaxVictoryPoints);
        }
        if ($queryMinDecrementPoints) {
            $query->where("decrement_points", ">=", $queryMinDecrementPoints);
        }
        if ($queryMaxDecrementPoints) {
            $query->where("decrement_points", "<=", $queryMaxDecrementPoints);
        }
        if ($queryLevel) {
            $query->where("level", $queryLevel);
        }
        if ($querySortByAttempts == "asc") {
            $query->orderBy("attempts", 'asc');
        }

        if ($querySortByAttempts == "desc") {
            $query->orderBy("attempts", "desc");
        }

        $games = $query->where("status", "active")->paginate(10);
        return response()->json([
            "data" => $games
        ]);
    }
    public function show(string $categoryId)
    {
        $games = Game::where("category_id", $categoryId)->where("status", "active")->get();
        return response()->json([
            "data" => $games
        ]);
    }
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
        $words = Word::where("game_id", $game->id)->get();
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
            "status" => "sometimes|in:active"
        ]);
        $game = Game::find($id);

        $game->update($validated);

        return response()->json([
            "game" => $game
        ]);
    }

    public function destroy(string $id)
    {
        $game = Game::findOrFail($id);
        $game->status = "deleted";
        $game->save();
        return response()->json(null, 204);
    }
}
