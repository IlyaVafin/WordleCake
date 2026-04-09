<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index() {
       $categories = Category::paginate(5);
       return response()->json([
        "categories" => $categories,
        "MESSAGE" => "HELLLO"
       ]);
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
}
