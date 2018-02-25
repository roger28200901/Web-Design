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

Route::post('/v1/auth/login', 'AuthController@login');
Route::get('/v1/place', 'PlacesController@index');

Route::middleware('auth.token')->group(function () {
    Route::get('/v1/auth/logout', 'AuthController@logout');

    Route::get('/v1/place/{id}', 'PlacesController@show');
    Route::post('/v1/place', 'PlacesController@store');
    Route::delete('/v1/place/{id}', 'PlacesController@destroy');
    Route::post('/v1/place/{id}', 'PlacesController@update');

    Route::post('/v1/schedule', 'SchedulesController@store');
    Route::delete('/v1/schedule/{id}', 'SchedulesController@destroy');

    Route::get('/v1/route/search/{from_place_id}/{to_place_id}/{departure_time?}', 'RoutesController@search');
    Route::post('/v1/route/selection', 'RoutesController@store');
});
