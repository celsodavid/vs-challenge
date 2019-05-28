<?php

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
    return $router->app->version();
});

$router->get('/key', function () use ($router) {
    return str_random(32);
});

$router->post('auth/login', [
    'uses' => 'AuthController@authenticate'
]);

$router->group([
    'middleware' => 'jwt.auth'
], function () use ($router) {
    $router->get('users', 'UserController@show');
});

$router->group([
    'middleware' => 'jwt.auth'
], function () use ($router) {
    $router->get('product', 'ProductController@show');
    $router->get('product/{id}', 'ProductController@get');
    $router->post('product', 'ProductController@create');
    $router->put('product/{id}', 'ProductController@edit');
    $router->delete('product/{id}', 'ProductController@delete');
    $router->get('products', 'ProductController@search');
});
