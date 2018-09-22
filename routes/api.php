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

    $api->get('/', function() {
        return [ 'Products' => 'Styled chair'];
    });

    $api->get('products', 'ProductsController@index');

    $api->get('product/{id}', 'ProductsController@show');

    $api->post('token', 'AuthController@authenticate');
    $api->post('logout', 'AuthController@logout');
    $api->get('refresh', 'AuthController@getToken');



});