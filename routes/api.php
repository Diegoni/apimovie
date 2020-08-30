<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


 Route::group(['prefix' => 'movies'], function() {
    if(env('APP_URL') == "http://localhost") {
        Route::group(['middleware' => 'auth:api'], function() {
            Route::get('{id}', 'MovieController@read')->where('id', '[0-9]+');
            Route::get('{viewState}', 'MovieController@index');
            Route::post('create', 'MovieController@create');
            Route::put('update/{movie}', 'MovieController@update');
            Route::delete('delete/{movie}', 'MovieController@delete');
        });
    } else {
        Route::get('{id}', 'MovieController@read')->where('id', '[0-9]+');
        Route::get('{viewState}', 'MovieController@index');
        Route::post('create', 'MovieController@create');
        Route::put('update/{movie}', 'MovieController@update');
        Route::delete('delete/{movie}', 'MovieController@delete');
    }
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});
