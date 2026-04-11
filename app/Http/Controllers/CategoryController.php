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

    public function getCategories(Request $request)
    {
        $query = Category::query();
        $queryCategoryName = $request->query("name");
        if ($queryCategoryName) {
            $query->where("name", "like", "%" . $queryCategoryName . "%");
        }
        $categories = $query->paginate(20);
        $mappedCategories = $categories->map(function($category) {
            return [
                "id" => $category->id,
                "name" => $category->name,
                "description" => $category->description,
                "preview" => $category->image
            ];
        });
        return response()->json([
            "data" => [
                "categories" => $mappedCategories,
                "per_page" => $categories->perPage(),
                "current_page" => $categories->currentPage(),
                "last_page" => $categories->lastPage(),
                "totalItems" => $categories->total()
            ]
        ]);
    }


    public function show(string $id)
    {
        $category = Category::find($id);
        $games = $category->games()->where("status", "active")->get();
        return view('category', ["category" => $category, "games" => $games]);
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
