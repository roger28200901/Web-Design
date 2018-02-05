<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // User::create($request->all());
        /* Setting Validation Rules */
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        /* Setting Error Messages */
        $messages = [
            'username.required' => 'invalid login',
            'password.required' => 'invalid login'
        ];

        /* Generating Validator */
        $validator = Validator::make($request->all(), $rules, $messages);

        /* Throwing Validator Exception */
        if ($validator->fails()) {
            abort(401, $validator->errors()->first());
        }

        /* Getting User via Username and Password */
        $user = User::where('username', $request->username)->first();
        if (!$user || Hash::check($user->password, $request->password)) {
            abort(401, 'invalid login'); // Throwing Invalid Login Exception
        }

        /* Setting User Login Status */
        $user->login();

        /* Returning Response */
        return response()->json(['token' => $user->token, 'role' => $user->role]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /* Findding User via Authorization Token */
        $user = User::where('token', $request->token)->firstOrFail();

        /* Unsetting User Login Status */
        $user->logout();

        /* Returning Response */
        return response()->json(['message' => 'logout success']);
    }
}
