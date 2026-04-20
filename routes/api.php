<?php

use App\Http\Controllers\AttemptController;
use App\Http\Controllers\AttemptsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/users", [UserController::class, 'index'])->middleware('auth:sanctum');

Route::post("/registration", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

Route::get('/category', [CategoryController::class, 'index']);
Route::get("/categories", [CategoryController::class, "getCategories"]);
Route::post('/category', [CategoryController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
Route::patch("/category/{id}", [CategoryController::class, "update"])->middleware(['auth:sanctum', 'admin']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);

Route::get("/games/{game_uuid}/info", [GameSessionController::class, 'show'])->middleware('auth:sanctum');
Route::get("/games", [GameController::class, 'index']);
Route::get("/games/{category_id}", [GameController::class, 'show']);
Route::post("/game", [GameController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
Route::post("/games/{game_id}", [GameSessionController::class, 'store'])->middleware("auth:sanctum");
Route::patch("/game/{id}", [GameController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
Route::post("/games/{game_uuid}/answer", [AttemptController::class, "store"])->middleware(['auth:sanctum']);
Route::delete("/game/{game_id}", [GameController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
Route::delete("/logout", [AuthController::class, 'logout'])->middleware(['auth:sanctum']);

Route::get("/profile", [ProfileController::class, 'index'])->middleware(['auth:sanctum']);
Route::patch("/profile", [ProfileController::class, 'update'])->middleware(['auth:sanctum']);

Route::post("/rewards/daily", [RewardController::class, 'store'])->middleware(['auth:sanctum']);
