<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenCookie = $request->cookie("access_token");
        $token = PersonalAccessToken::findToken($tokenCookie);

        if ($token && $token->tokenable) {
            auth()->login($token->tokenable);
            return $next($request);
        }

        if ($request->is("api/*")) {
            return response()->json(["message" => "Unauthorized"], 401)->withoutCookie("access_token");
        }
        return redirect("/auth/login")->withoutCookie("access_token");
    }
}
