<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;
use Validator;
use Storage;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int $album_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($album_id, Request $request)
    {
        /* Checking excess data */
        if (count(array_diff_key($request->all(), ['title' => '', 'description' => '', 'image' => '']))) {
            abort(400, '無效的輸入資料');
        }

        /* Rules of validation */
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image',
        ];

        /* Messages of errors */
        $messages = [
            'title.required' => '無效的輸入資料',
            'description.required' => '無效的輸入資料',
            'image.required' => '無效的輸入資料',
            'image.image' => '無效的輸入資料',
        ];

        /* Execute the validator */
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }

        /* Storing into storage */
        $path = $request->image->store('/', 'upload');

        /* Getting image info */
        $link = url("/i/$path");
        list($width, $height) = getimagesize($link); // Getting width and height of image
        $size = $request->image->getClientSize(); // Getting size of image
        $image_info = [
            'width' => $width,
            'height' => $height,
            'size' => $size,
            'link' => $link,
        ];

        /* Storing model */
        $data = array_merge($request->except('image'), $image_info);
        $image = Album::where('album_id', $album_id)->firstOrFail()->images()->create($data);

        /* Compacting data */
        $id = $image->image_id;
        $data = compact('id');
        return response()->view('successes.show-id', $data, 200)
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
