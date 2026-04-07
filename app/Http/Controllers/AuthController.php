<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', "register"]]);
    }

    public function showRegister()
    {
        return view("register");
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            "email" => "required|email|unique:users,email",
            "nickname" => "required|string|unique:users,nickname|min:1",
            "password" => "required|string|min:3",
            "first_name" => "required|string",
            "last_name" => "required|string",
            "birthday" => "required|before_or_equal:today",
            "avatar" => "nullable|image|mimes:png,jpg,jpeg|max:2048"
        ]);
        $path = "";
        if ($request->hasFile("avatar")) {
            $path = Storage::disk("public")->putFile("avatars", $request->file("avatar"));
        }
         
        $user = User::create([
            "email" => $validated['email'],
            "nickname" => $validated['nickname'],
            "password" => Hash::make($validated['password']),
            "first_name" => $validated['first_name'],
            "last_name" => $validated["last_name"],
            "birthday" => $validated["birthday"],
            "avatar" => $path,
            "points" => 100,
        ]);
        $token = auth('api')->login($user);

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
        ], 201);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth('api')->user();
        return response()->json([
            "message" => "success login in!",
            "data" => [
                "user" => [
                    "nickname" => $user['nickname'],
                    "avatar" => $user['avatar']
                ],
            ],
            "credentials" => [
                "token" => $token
            ]
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
