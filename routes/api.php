<?php

$api = app('Dingo\Api\Routing\Router');
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

/* Version v1 api endpoints */
$api->version('v1', function (Dingo\Api\Routing\Router $api) {
    $api->get('/', function() {
        return 'dingo api';
    });

    $api->group(['prefix' => 'users', 'namespace' => 'App\Http\Controllers'], function (Dingo\Api\Routing\Router $api) {
        $api->get('/', 'UserController@index');
        $api->post('/', 'UserController@store');
        $api->get('/{id}', 'UserController@getUserById');
        $api->put('/{id}' , 'UserController@updateUserById');
    });

    $api->group(['prefix' => 'posts', 'namespace' => 'App\Http\Controllers'], function (Dingo\Api\Routing\Router $api) {
        $api->get('/', 'PostController@index');
        $api->get('/{id}', 'PostController@getByPostId');
        $api->put('/{id}', 'PostController@updateByPostId');
        $api->delete('/{id}', 'PostController@deleteByPostId');
        $api->post('/', 'PostController@store');
    });
});
