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

Route::get('/', function() {
    return 'api index';
});

Route::prefix('users')->group(function() {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@store');
    Route::get('/{id}', 'UserController@getUserById');
    Route::put('/{id}' , 'UserController@updateUserById');
});

Route::prefix('posts')->group(function() {
    Route::get('/', 'PostController@index');
    Route::get('/{id}', 'PostController@getByPostId');
    Route::put('/{id}', 'PostController@updateByPostId');
    Route::delete('/{id}', 'PostController@deleteByPostId');
    Route::post('/', 'PostController@store');
});
