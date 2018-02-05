<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class VerifyAuthorizationToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Findding User via Authorization Token */
        User::where('token', $request->token)->firstOrFail();

        return $next($request);
    }
}
