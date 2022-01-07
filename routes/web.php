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

// autentikasi akun
$router->post('register','AuthController@register');
$router->post('login','AuthController@login');

// nongskuy
$router->get('nongskuy/terdekat', 'NongskuyController@terdekat');
$router->get('nongskuy/populer', 'NongskuyController@populer');
$router->get('nongskuy/{id}', 'NongskuyController@show');
$router->get('nongskuy/{id}/menu', 'NongskuyController@menu');
$router->post('nongskuy/search', 'NongskuyController@search');

$router->group(['middleware' => 'auth'], function() use ($router){
    // konfigruasi akun
    $router->post('logout', 'AuthController@logout');
    $router->put('ubahpassword', 'ProfilController@ubahPassword');
    $router->put('ubahprofil', 'ProfilController@ubahProfil');

    // promo
    $router->get('promo', 'PromoController@index');

    // pemesanan
    $router->get('pesan/riwayat', 'PesanController@riwayat');

    // review
    $router->get('nongskuy/{id}/review', 'ReviewController@review');
});
