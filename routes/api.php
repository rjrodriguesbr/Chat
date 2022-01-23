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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/users', 'Api\UserController@index')->name('user.index');
    Route::get('/users/{user}', 'Api\UserController@show')->name('user.show');
    Route::get('/me', 'Api\UserController@me')->name('user.me');

    Route::get('/messages/{user}', 'Api\MessageController@listMessages')->name('messages.listMessages');
    Route::post('/messages/store', 'Api\MessageController@store')->name('messages.store');
});
