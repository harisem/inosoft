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

    Route::middleware('jwt.verify')->group(function () {
        Route::post('refresh', 'UserController@refresh')->name('refresh');
        Route::post('logout', 'UserController@logout')->name('logout');
        Route::post('me', 'UserController@me')->name('me');
    });
});

Route::prefix('/kendaraan')->middleware('jwt.verify')->group(function () {
    Route::get('', 'KendaraanController@allStok')->name('get_stok');
    Route::get('terjual', 'KendaraanController@allTerjual')->name('get_terjual');
    Route::get('{id}', 'KendaraanController@kendaraan')->name('get_kendaraan');
    Route::post('', 'KendaraanController@storeKendaraan')->name('store_kendaraan');
    Route::post('terjual/{id}', 'KendaraanController@storePenjualan')->name('store_kendaraan_terjual');
});
