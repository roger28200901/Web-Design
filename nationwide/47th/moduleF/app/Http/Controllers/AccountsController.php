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
        /* Getting xml data */
        $data = simplexml_load_string($request->getContent()); // Interprets a string of XML into an object

        $data = json_decode(json_encode($data), true); // Converting SimpleXML object to an array

        /* Checking excess data */
        if (count(array_diff_key($data, ['account' => '', 'bio' => '']))) {
            abort(400, '無效的輸入資料');
        }

        /* Rules of validation */
        $rules = [
            'account' => 'required|unique:accounts',
            'bio' => 'required',
        ];

        /* Messages of errors */
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
        $id = $account->account_id;
        $data = compact('id');
        return response()->view('successes.show-id', $data, 200)
                         ->header('content-type', 'application/xml');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $account_id
     * @return \Illuminate\Http\Response
     */
    public function show($account_id)
    {
        $account = Account::where('account_id', $account_id)->with('albums')->firstOrFail();
        foreach ($account->albums as $album) {
            $album->count = $album->images->count();
        }

        $data = compact('account');
        return response()->view('successes.show-userinfo', $data, 200)
                         ->header('content-type', 'application/xml');
    }
}
