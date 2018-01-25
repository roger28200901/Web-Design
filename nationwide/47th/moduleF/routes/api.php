<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/account', 'AccountsController@store');

Route::get('/album/{albumID}', 'AlbumsController@show');

Route::get('/album/{albumID}/latest', 'AlbumsController@latest');

Route::get('/album/{albumID}/hot', 'AlbumsController@hot');

Route::get('/album/{albumID}/images/{imageID}', 'ImagesController@show');

Route::delete('/album/{albumID}/images/{imageID}', 'ImagesController@destroy');

Route::get('/i/{imageID}{imageSuffix}.jpg', 'ImagesController@image');

Route::middleware('auth.token')->group(function () {

    Route::get('/account/{accountID}', 'AccountsController@show');

    Route::post('/album/{accountID}/image', 'ImagesController@store');

    Route::patch('/album/{albumID}/images/{imageID}', 'ImagesController@update');

    Route::resource('/album', 'AlbumsController', ['except' => 'show']);

});
