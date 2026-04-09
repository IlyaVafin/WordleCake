<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::withCount([
            'games',
            'games as easy_count' => function ($query) {
                $query->where("level", "=", "easy");
            },
            'games as medium_count' => function ($query) {
                $query->where("level", "=", "medium");
            },
            'games as hard_count' => function ($query) {
                $query->where("level", "=", "hard");
            },
        ])->paginate(5);

        return response()->json([
            "categories" => $categories
        ]);
    }

    public function show(string $id)
    {
        $category = Category::with("games")->where("id", "=", $id)->first();
        return view('category', ["category" => $category]);
    }

    public function store(Request $request)
    {
        $categoryData = $request->validate([
            "name" => "required|string|max:15",
            "description" => "sometimes|nullable|max:50",
            "image" => "required|mimes:jpg,jpeg,png|max:4608"
        ]);
        $path = Storage::disk("public")->putFile("category", $request->file("image"));
        $category = Category::create([
            "name" => $categoryData['name'],
            "description" => $categoryData['description'] ?? "",
            "image" => $path
        ]);
        return response()->json($category, 201);
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json(["message" => "Category not found"], 404);
        }
        $category->delete();
        return response(null, 204);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            "image" => "sometimes|image|mimes:jpg,png,jpeg|max:2048",
            "name" => "sometimes|string|max:15",
            "description" => "sometimes|nullable|string|max:50"
        ]);
        $category = Category::findOrFail($id);

        if ($request->hasFile("image")) {
            Storage::disk("public")->delete($category->image);
            $validated['image'] = Storage::disk("public")->putFile("category", $request->file("image"));
        }

        $category->update($validated);

        return response()->json([
            "category" => $category
        ]);
    }
}
