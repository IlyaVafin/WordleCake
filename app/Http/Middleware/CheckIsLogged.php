<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsLogged
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenCookie = $request->cookie("access_token");
        if($tokenCookie) {
            $token = PersonalAccessToken::findToken($tokenCookie);
            if($token && $token->tokenable()) {
                return redirect("/");
            }
        }
        return $next($request);
    }
}
