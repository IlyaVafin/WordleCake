<?php

use App\Http\Controllers\AttemptController;
use App\Http\Controllers\AttemptsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store'])->middleware(['check-auth', 'admin']);
Route::patch("/category/{id}", [CategoryController::class, "update"])->middleware(['check-auth', 'admin']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->middleware(['check-auth', 'admin']);

Route::get("/games/{game_uuid}/info", [GameSessionController::class, 'show']);
Route::post("/game", [GameController::class, 'store'])->middleware(['check-auth', 'admin']);
Route::post("/games/{game_id}", [GameSessionController::class, 'store'])->middleware(['check-auth']);
Route::patch("/game/{id}", [GameController::class, 'update'])->middleware(['check-auth', 'admin']);
Route::post("/games/{game_uuid}/answer", [AttemptController::class, "store"])->middleware(['check-auth']);
