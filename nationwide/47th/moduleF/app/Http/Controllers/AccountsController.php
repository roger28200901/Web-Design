<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Validator;

class AccountsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = simplexml_load_string($request->getContent()); // Interprets a string of XML into an object

        $data = json_decode(json_encode($data), true); // Converting SimpleXML object to an array

        if (count(array_diff_key($data, ['account' => '', 'bio' => '']))) {
            abort(400, '無效的輸入資料');
        }

        /*** Validating data ***/
        /* Rules of validating */
        $rules = [
            'account' => 'required|unique:accounts',
            'bio' => 'required',
        ];

        /* Messages of Errors */
        $messages = [
            'account.required' => '無效的輸入資料',
            'account.unique' => '此帳號已經被註冊',
            'bio.required' => '無效的輸入資料',
        ];

        /* Execute the validator */
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }

        /* Storing model */
        $account = Account::create($data);

        /* Compacting data */
        $token = $account->token;
        $data = compact('token');
        return response()->view('successes.show-token', $data, 200)
                         ->header('content-type', 'application/xml');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::findOrFail($id);
    }
}
