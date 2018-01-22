<?php

namespace App\Http\Middleware;

use Closure;
use App\Account;

class TokenAuthorization
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
        /* Getting authorization data */
        $authorizations = $request->header('authorization');
        if ('' == $authorizations) {
            abort(403, '拒絕存取');
        }

        /* Comparing token */
        $authorizations = explode(', ', $authorizations);
        foreach ($authorizations as $authorization) {
            list($index, $value) = explode('=', $authorization, 2);
            if ('token' === $index) {
                Account::where('token', $value)->firstOrFail();
                return $next($request);
            }
        }

        abort(403, '拒絕存取');
    }
}
