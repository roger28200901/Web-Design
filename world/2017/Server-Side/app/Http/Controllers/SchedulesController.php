<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Schedule;
use Validator;

class SchedulesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Checking User Role */
        if ('ADMIN' !== $request->userRole) {
            abort(422, 'data cannot be processed');
        }

        /* Retrieving A Portion Of The Input Data */
        $data = $request->except(['token', 'userRole']);

        /* Setting Validation Rules */
        $rules = [
            'type' => 'required|in:TRAIN,BUS',
            'line' => 'required',
            'from_place_id' => 'required',
            'to_place_id' => 'required',
            'departure_time' => 'required',
            'arrival_time' => 'required',
            'distance' => 'required',
            'speed' => 'required'
        ];

        /* Filtering Redundant Data(s) */
        if (count(array_diff_key($data, $rules))) {
            abort(422, 'data cannot be processed');
        }

        /* Generating Validator */
        $validator = Validator::make($data, $rules);

        /* Throwing Validator Exception */
        if ($validator->fails()) {
            abort(422, 'data cannot be processed');
        }

        Schedule::create($data);

        /* Returning Response */
        return response()->json(['message' => 'create success']);
    }
}
