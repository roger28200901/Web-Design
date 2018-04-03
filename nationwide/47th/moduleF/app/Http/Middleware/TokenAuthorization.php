<?php

namespace App\Http\Middleware;

use Closure;
use App\Account;
use Validator;

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
        /* Rules of validation */
        $rules = [
            'authorization' => 'required',
        ];

        /* Messages of errors */
        $messages = [
            'authorization.required' => '拒絕存取',
        ];

        /* Execute the validator */
        $validator = Validator::make($request->header(), $rules, $messages);
        if ($validator->fails()) {
            abort(403, $validator->errors()->first());
        }

        /* Comparing token */
        $authorizations = explode(', ', $request->header('authorization'));
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
