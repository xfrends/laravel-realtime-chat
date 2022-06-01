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

Route::post('login', 'AuthController@login');
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {
    Route::post('logout', 'AuthController@logout');
    Route::post('logoutalldevice', 'AuthController@logoutAllDevice');
    Route::get('profile', 'AuthController@profile');
});
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::resource('users', 'UserController')->only([
        'index', 'show'
    ]);
    Route::resource('contact', 'ContactController')->only([
        'index', 'store'
    ]);
});
