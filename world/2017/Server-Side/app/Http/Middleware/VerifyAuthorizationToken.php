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
        /* Findding User Via Authorization Token */
        $user = User::where('token', $request->token)->first();

        /* Throwing User Not Found Exception */
        if (!$user) {
            abort(401, 'Unauthorized user');
        }

        /* Putting User Role Into Input Data */
        $request->request->add(['userRole' => $user->role]);

        return $next($request);
    }
}
