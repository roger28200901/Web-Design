<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;
use App\Image;
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
     * @param  string $album_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $album_id)
    {
        /* Checking excess data */
        if (count(array_diff_key($request->all(), ['title' => '', 'description' => '', 'image' => '']))) {
            abort(400, '無效的輸入資料');
        }

        /* Rules of validation */
        $rules = [
            'title' => 'required',
            'image' => 'required|image',
        ];

        /* Messages of errors */
        $messages = [
            'title.required' => '無效的輸入資料',
            'image.required' => '無效的輸入資料',
            'image.image' => '無效的輸入資料',
        ];

        /* Execute the validator */
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }

        /* Storing into storage */
        $filename = $this->storeImage($request->image);

        /* Getting image info */
        $link = url("/i/$filename");
        list($width, $height) = getimagesize(base_path("images/$filename")); // Getting width and height of image
        $size = filesize(base_path("images/$filename")); // Getting size of image
        $image_info = [
            'filename' => $filename,
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
     * @param  int  $album_id
     * @param  int  $image_id
     * @return \Illuminate\Http\Response
     */
    public function show($album_id, $image_id)
    {
        $image = Album::where('album_id', $album_id)->firstOrFail()->images()->where('image_id', $image_id)->firstOrFail();
        $data = compact('image');
        return response()->view('successes.show-imageinfo', $data, 200)
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
     * @param  string  $album_id
     * @param  string  $image_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $album_id, $image_id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $album_id
     * @param  string  $image_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($album_id, $image_id)
    {
        Image::where('image_id', $image_id)->firstOrFail()->delete();
        return response()->view('successes.show-id', [], 200)
                         ->header('content-type', 'application/xml');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\UploadedFile $image
     * @return string $filename
     */
    private function storeImage($image)
    {
        /* Create image from type */
        switch ($image->getMimeType()) {
            case 'image/png':
                $image_original = imagecreatefrompng($image);
                break;
            case 'image/gif':
                $image_original = imagecreatefromgif($image);
                break;
            default:
                $image_original = imagecreatefromjpeg($image);
                break;
        }

        /* Storing */
        $filename = md5(uniqid(rand())) . '.jpg';
        $quality = 40;
        imagejpeg($image_original, base_path("images/$filename"), $quality);

        return $filename;
    }
}
