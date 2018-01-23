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

Route::get('/album/{id}', 'AlbumsController@show');

Route::middleware('auth.token')->group(function () {

    Route::get('/account/{id}', 'AccountsController@show');

    Route::post('/album/{id}/image', 'ImagesController@store');

    Route::resource('/album', 'AlbumsController', ['except' => 'show']);

});
