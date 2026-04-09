<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store'])->middleware(['check-auth', 'admin']);
Route::patch("/category/{id}", [CategoryController::class, "update"]);
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->middleware(['check-auth', 'admin']);

Route::post("/game", [GameController::class, 'store']);
