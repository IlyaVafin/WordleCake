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

Route::get("/users", [UserController::class, 'index']);

Route::get('/category', [CategoryController::class, 'index']);
Route::get("/categories", [CategoryController::class, "getCategories"]);
Route::post('/category', [CategoryController::class, 'store'])->middleware(['check-auth', 'admin']);
Route::patch("/category/{id}", [CategoryController::class, "update"])->middleware(['check-auth', 'admin']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->middleware(['check-auth', 'admin']);

Route::get("/games/{game_uuid}/info", [GameSessionController::class, 'show'])->middleware('check-auth');
Route::get("/games", [GameController::class, 'index']);
Route::get("/games/{category_id}", [GameController::class, 'show']);
Route::post("/game", [GameController::class, 'store'])->middleware(['check-auth', 'admin']);
Route::post("/games/{game_id}", [GameSessionController::class, 'store'])->middleware(['check-auth']);
Route::patch("/game/{id}", [GameController::class, 'update'])->middleware(['check-auth', 'admin']);
Route::post("/games/{game_uuid}/answer", [AttemptController::class, "store"])->middleware(['check-auth']);
Route::delete("/game/{game_id}", [GameController::class, 'destroy'])->middleware(['check-auth', 'admin']);
Route::delete("/logout", [AuthController::class, 'logout'])->middleware(['check-auth']);

Route::get("/profile", [ProfileController::class, 'index']);
Route::patch("/profile", [ProfileController::class, 'update']);

Route::post("/rewards/daily", [RewardController::class, 'store']);