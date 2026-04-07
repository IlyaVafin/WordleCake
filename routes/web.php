<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view("welcome", ["name" => "Ilya"]);
});
Route::get("/registration", function () {
    return view("register");
});
Route::get("/auth/login", function () {
    return view("login");
});

Route::middleware("auth:api")->group(function () {
    Route::get("/profile", function () {
        return view('profile');
    });
});



Route::post("/", function (Request $request) {
    $message = $request->input("message");
    return response()->json(["message" => $message]);
});



Route::post("/registration", [AuthController::class, "register"]);

Route::prefix("auth")->group(function () {
    Route::post("login", [AuthController::class, 'login'])->name("login");
});
