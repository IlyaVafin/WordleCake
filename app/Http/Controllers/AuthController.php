<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view("register");
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            "email" => "string|required|unique:users,email",
            "nickname" => "string|required|unique:users,nickname|min:1",
            "password" => "string|required|min:3",
            "first_name" => "string|required",
            "last_name" => "string|required",
            "birthday" => "required|before_or_equal:today",
            "avatar" => "mimes:jpg,png,jpeg|max:2048"
        ]);
        $path = "";
        if ($request->hasFile("avatar")) {
            $path = Storage::disk('public')->putFile("avatars", $request->file("avatar"));
        }

        $user = User::create([
            "email" => $validated['email'],
            "nickname" => $validated['nickname'],
            "password" => Hash::make($validated['password']),
            "first_name" => $validated["first_name"],
            "last_name" => $validated["last_name"],
            "birthday" => $validated["birthday"],
            "avatar" => $path,
            "points" => 100
        ]);

        $token = $user->createToken("auth-token")->plainTextToken;

        return response()->json([
            "message" => "registration success!!",
            "data" => [
                "user" => [
                    "nickname" => $user['nickname'],
                    "avatar" => $user['avatar'],
                ],
            ],
            "credentials" => [
                "token" => $token
            ]
        ], 201)->cookie('access_token', $token, 120, "/", null, true, true);
    }

    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            "email" => "required|string|email",
            "password" => "required|string|min:3"
        ]);
        $user = User::where('email', $loginUserData['email'])->first();
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json(["message" => "Invalid credentials"], 401);
        }
        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            "message" => "success login in!",
            "data" => [
                "user" => [
                    "nickname" => $user['nickname'],
                    "avatar" => $user['avatar']
                ]
            ],
            "credentials" => [
                "token" => $token
            ]
        ])->cookie("access_token", $token, 120, "/", null, true, true);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return redirect("/");
    }
}
