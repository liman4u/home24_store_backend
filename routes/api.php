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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
    'namespace' => 'App\Http\Controllers'
], function ($api) {


    $api->get('products', 'ProductsController@index');

    $api->get('product/{id}', 'ProductsController@show');

    $api->post('token', 'AuthController@authenticate');
    $api->post('logout', 'AuthController@logout');
    $api->get('refresh', 'AuthController@refreshToken');
    $api->post('register', 'AuthController@register');


    $api->group([
        'middleware' => 'api.auth',
    ], function ($api) {

        $api->get('account', 'AuthController@authenticatedUser');

        $api->post('products', 'ProductsController@store');

        $api->delete('products/{id}', 'ProductsController@destroy');

    });

});

