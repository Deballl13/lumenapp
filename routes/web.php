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

$router->get('/', function () use ($router) {
    return ["Nongskuy"];
});

$router->post('/register','AuthController@register');
$router->post('/login','AuthController@login');

$router->group(['middleware' => 'auth'], function() use ($router){
    $router->post('/logout', 'AuthController@logout');
    $router->put('/ubahpassword', 'ProfilController@ubahPassword');
    $router->put('/ubahprofil', 'ProfilController@ubahProfil');

    $router->get('/promo', 'PromoController@index');
    $router->get('/pesan/riwayat', 'PesanController@riwayat');
});
