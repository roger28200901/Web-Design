<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Album;
use App\Image;
use Validator;

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
     * Display the specified resource as image/jpeg.
     *
     * @param  int  $image_id
     * @param  int  $image_suffix
     * @return \Illuminate\Http\Response
     */
    public function image($image_id, $image_suffix)
    {
        /* Getting image */
        if (!$image = Image::where('image_id', $image_id)->first()) {
            $image_id = $image_id . $image_suffix;
            $image_suffix = '';
            $image = Image::where('image_id', $image_id)->firstOrFail();
        }

        /* Reconizing suffix */
        if (!in_array($image_suffix, ['', 'l', 'm', 's', 't'])) {
            abort(400, '無效的輸入資料');
        }

        /* Creating image */
        $path = base_path('images/' . $image->filename);
        $image_original = imagecreatefromjpeg($path);

        /* Calculating image size */
        $image_original_width = imagesx($image_original);
        $image_original_height = imagesy($image_original);
        switch($image_suffix) {
            case 'l':
                $image_resized_width = min(960, $image_original_width);
                $image_resized_height = min(960, $image_original_height);
                break;
            case 'm':
                $image_resized_width = min(320, $image_original_width);
                $image_resized_height = min(320, $image_original_height);
                break;
            case 's':
                $image_resized_width = min(90, $image_original_width);
                $image_resized_height = min(90, $image_original_height);
                break;
            case 't':
                $image_resized_width = 50;
                $image_resized_height = 50;
                break;
            default:
                $image_resized_width = $image_original_width;
                $image_resized_height = $image_original_height;
                break;
        }

        if ('t' != $image_suffix && ($image_resized_width != $image_original_height || $image_resized_height != $image_original_height)) {
            if ($image_original_width > $image_original_height) {
                $resize_ratio = $image_resized_width / $image_original_width;
                $image_resized_height = ceil($image_original_height * $resize_ratio);
            } else {
                $resize_ratio = $image_resized_height / $image_original_height;
                $image_resized_width = ceil($image_original_width * $resize_ratio);
            }
        }

        /* Creating image resized */
        $image_resized = imagecreatetruecolor($image_resized_width, $image_resized_height);
        imagecopyresampled($image_resized, $image_original, 0, 0, 0, 0,
                         $image_resized_width,
                         $image_resized_height,
                         $image_original_width,
                         $image_original_height);

        /* Reading to buffer */
        ob_start();
            imagejpeg($image_resized);
            $jpeg_file_content = ob_get_contents();
            imagedestroy($image_resized);
            imagedestroy($image_original);
        ob_end_clean();

        return response($jpeg_file_content, 200)->header('content-type', 'image/jpeg');
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
        //
    }

    /**
     * Move the specified resource to another album.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        /* Getting xml data */
        $data = simplexml_load_string($request->getContent());

        $data = json_decode(json_encode($data), true);


        /* Checking excess data */
        if (count(array_diff_key($data, ['src_image' => '', 'dst_album' => '']))) {
            abort(400, '無效的輸入資料');
        }

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

        $account = Account::where('token', $token)->firstOrFail();

        /* Checking album isvalid */
        $album_destination = $account->albums()->where('album_id', $data['dst_album'])->firstOrFail();

        /* Checking image isvalid */
        $is_valid = false;
        foreach ($account->albums as $album) {
            if ($image = $album->images()->where('image_id', $data['src_image'])->first()) {
                $is_valid = true;
                break;
            }
        }

        if (!$is_valid) {
            abort(404, '無效的輸入資料');
        }

        /* Moving to destination album */
        $image->update(['album_id' => $album_destination->id]);

        $status_code = 204;
        $data = compact($status_code);
        return response()->view('successes.show-status', $data, 200)
                         ->header('content-type', 'application/xml');
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
        $status_code = 204;
        $data = compact($status_code);
        return response()->view('successes.show-status', $data, 200)
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
