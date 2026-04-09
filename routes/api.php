<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/category', [CategoryController::class, 'index']);
Route::post('/category', [CategoryController::class, 'store'])->middleware(['check-auth', 'admin']);
Route::delete('/category', [CategoryController::class, 'destroy'])->middleware(['check-auth', 'admin']);
