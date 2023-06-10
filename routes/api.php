<?php

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

Route::prefix('/auth')->group(function () {
    Route::post('register', 'UserController@register')->name('register');
    Route::post('login', 'UserController@login')->name('login');

    Route::middleware('api')->group(function () {
        Route::post('refresh', 'UserController@refresh')->name('refresh');
        Route::post('logout', 'UserController@logout')->name('logout');
        Route::post('me', 'UserController@me')->name('me');
    });
});
