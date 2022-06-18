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

Route::post('user', 'UserController@store');
Route::post('login', 'AuthController@login');
Route::group(['prefix' => 'auth', 'middleware' => 'auth:sanctum'], function() {
    Route::post('logout', 'AuthController@logout');
    Route::post('logoutalldevice', 'AuthController@logoutAllDevice');
    Route::get('profile', 'AuthController@profile');
});
Route::group(['middleware' => ['auth:sanctum','rolecheck']], function() {
    Route::resource('user', 'UserController')->only([
        'index', 'show', 'update'
    ]);
    Route::put('user-manage/{id}', 'UserController@manage');
    Route::resource('contact', 'ContactController')->only([
        'index', 'store', 'destroy'
    ]);
    Route::resource('chat', 'ChatController')->only([
        'index', 'show', 'store', 'destroy'
    ]);
    Route::post('chat-pin/{id}', 'ChatController@pin');
    Route::resource('message', 'MessageController')->only([
        'index', 'store'
    ]);
});
