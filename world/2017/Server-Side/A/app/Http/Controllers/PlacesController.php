<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Place;
use Storage;

class PlacesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Getting A Listing Of The Places */
        $places = Place::all();

        /* Returning Response */
        return response()->json($places);
    }

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
            'place_id' => 'required|unique:places',
            'name' => 'required|unique:places',
            'latitude' => 'required',
            'longitude' => 'required',
            'x' => 'required',
            'y' => 'required',
            'image' => 'required',
            'description' => ''
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

        /* Checking Duplicated Row(s) */
        if (Place::where(['latitude' => $data['latitude'], 'longitude' => $data['longitude']])->orWhere(['x' => $data['x'], 'y' => $data['y']])->first()) {
            abort(422, 'data cannot be processed');
        }

        /* Checking Extension Of Image */
        if (!preg_match('/(image)\.*/', $data['image']->getMimeType())) {
            abort(422, 'data cannot be processed');
        }

        /* Storing Image */
        $image_path = $data['image']->store('places');

        /* Compacting Data */
        unset($data['image']);
        $data['image_path'] = $image_path;
        Place::create($data);

        /* Returning Response */
        return response()->json(['message' => 'create success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* Retrieving Place Via ID */
        $place = Place::find($id);

        /* Returning Response */
        return response()->json($place);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /* Checking User Role */
        if ('ADMIN' !== $request->userRole) {
            abort(400, 'data cannot be updated');
        }

        /* Retrieving A Portion Of The Input Data */
        $data = $request->except(['token', 'userRole']);

        /* Filtering Redundant Data(s) */
        if (count(array_diff_key($data, ['place_id' => '', 'name' => '', 'latitude' => '', 'longitude' => '', 'x' => '', 'y' => '', 'image' => '', 'description' => '']))) {
            abort(400, 'data cannot be updated');
        }

        /* Getting Place */
        $place = Place::find($id);

        if (isset($data['image'])) {
            /* Checking Extension Of Image */
            if (!preg_match('/(image)\.*/', $data['image']->getMimeType())) {
                abort(400, 'data cannot be processed');
            }

            /* Unlinking Image File */
            Storage::delete($place->image_path);

            /* Storing Image */
            $image_path = $data['image']->store('/');

            /* Compacting Data */
            unset($data['image']);
            $data['image_path'] = $image_path;
        }

        $place->update($data);

        /* Returning Response */
        return response()->json(['message' => 'update success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        /* Checking User Role */
        if ('ADMIN' !== $request->userRole) {
            abort(400, 'data cannot be deleted');
        }

        /* Getting Place */
        $place = Place::find($id);

        if ($place) {
            /* Unlinking Image File */
            Storage::delete($place->image_path);

            /* Deleting Place */
            $place->delete();
        }

        /* Returning Response */
        return response()->json(['message' => 'delete success']);
    }
}
