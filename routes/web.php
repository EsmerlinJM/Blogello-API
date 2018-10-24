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

$router->get('/key', function() {
    return str_random(32);
});

$router->group(['prefix' => 'api/v1'], function() use($router) {
    // TODO: Login user
    $router->post('/users/login', ['uses' => 'UserController@getToken']);

    $router->group(['middleware' => ['auth']], function() use($router) {
        // TODO: CRUD users
    $router->get('/users', ['uses' => 'UserController@index']);
    $router->post('/users', ['uses' => 'UserController@store']);
    $router->put('/users/{id}', ['uses' => 'UserController@update']);
    $router->delete('/users/{id}', ['uses' => 'UserController@destroy']);
    });
    
});

