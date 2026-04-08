<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isApi = $request->is("api/**");
        $user = $request->user(); 
        if(!$user || !$user->superuser) {
            if($isApi) {
                return response()->json(["message" => "Forbidden"], 403); 
            }
            return redirect("/");
        }
        return $next($request); 
    }
}
