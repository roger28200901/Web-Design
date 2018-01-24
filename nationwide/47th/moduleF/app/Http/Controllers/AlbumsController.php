<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Album;
use Validator;

class AlbumsController extends Controller
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
     * Display a latest of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function latest($album_id)
    {
        /* Getting latest 3 images */
        $album = Album::where('album_id', $album_id)->with(['images' => function ($query) {
            $query->orderBy('created_at', 'desc')->take(3);
        }])->firstOrFail();

        /* Compacting data */
        $data = compact('album');
        $album->link = url("/album/$album_id");
        $album->images_count = $album->images->count();
        return response()->view('successes.show-images', $data, 200)
                         ->header('content-type', 'application/xml');
    }

    /**
     * Display a most view times of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function hot($album_id)
    {
        /* Getting 3 most view times images */
        $album = Album::where('album_id', $album_id)->with(['images' => function ($query) {
            $query->orderBy('views', 'desc')->take(3);
        }])->firstOrFail();

        /* Compacting data */
        $data = compact('album');
        $album->link = url("/album/$album_id");
        $album->images_count = $album->images->count();
        return response()->view('successes.show-images', $data, 200)
                         ->header('content-type', 'application/xml');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Getting token */
        $token = '';
        $authorizations = explode(', ', $request->header('authorization'));
        foreach ($authorizations as $authorization) {
            list($index, $value) = explode('=', $authorization, 2);
            if ('token' === $index) {
                $token = $value;
                break;
            }
        }

        /* Getting xml data */
        $data = simplexml_load_string($request->getContent());

        $data = json_decode(json_encode($data), true);

        /* Checking excess data */
        if (count(array_diff_key($data, ['title' => '', 'description' => '']))) {
            abort(400, '無效的輸入資料');
        }

        /* Rules of validation */
        $rules = [
            'title' => 'required',
        ];

        /* Messages of errors */
        $messages = [
            'title.required' => '無效的輸入資料',
        ];

        /* Execute the validator */
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }

        /* Storing model */
        $album = Account::where('token', $token)->firstOrFail()->albums()->create($data);

        /* Compacting data */
        $id = $album->album_id;
        $data = compact('id');
        return response()->view('successes.show-id', $data, 200)
                         ->header('content-type', 'application/xml');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $album_id
     * @return \Illuminate\Http\Response
     */
    public function show($album_id)
    {
        $album = Album::where('album_id', $album_id)->with(['account', 'images'])->firstOrFail();
        $album->images_count = $album->images->count();
        $album->link = url()->current();
        $data = compact('album');
        return response()->view('successes.show-albuminfo', $data, 200)
                         ->header('content-type', 'application/xml');
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
