<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return ["Hello Hai..!!!"];
// });

// $router->get('/customer', function () use ($router) {
//     $results = app('db')->select("SELECT * FROM customer");
//     return response()->json($results);
// });

// $router->get('/toko', function () use ($router) {
//     $results = app('db')->select("SELECT * FROM toko");
//     return response()->json($results);
// });

// $router->post('/register', 'UserController@register');
// $router->post('/login','AuthController@login');
$router->post('/testing','UserController@test');


// $router->group(['middleware' => 'auth'], function() use ($router){
//     $router->post('/logout', 'AuthController@logout');
// });
