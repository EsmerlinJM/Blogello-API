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
    // TODO: Middleware validate header json
    $router->group(['middleware' => ['json']], function() use($router) {
        // TODO: Login user
        $router->post('/users/login', ['uses' => 'Auth\AuthController@login']);

        // TODO: Register user
        $router->post('/users/register', ['uses' => 'Auth\AuthController@register']);

        // TODO: Validate is user is authenticate
        $router->group(['middleware' => ['auth']], function() use($router) {
            // TODO: CRUD users
            $router->get('/users', ['uses' => 'UserController@index']);
            $router->get('/users/{id}', ['uses' => 'UserController@show']);
            $router->post('/users', ['uses' => 'UserController@store']);
            $router->put('/users/{id}', ['uses' => 'UserController@update']);
            $router->delete('/users/{id}', ['uses' => 'UserController@destroy']);

            // TODO: Logout user
            $router->post('/users/logout', ['uses' => 'Auth\AuthController@logout']);

            // TODO:CRUD boards
            $router->get('/boards', ['uses' => 'BoardController@index']);
            $router->get('/boards/{id}', ['uses' => 'BoardController@show']);
            $router->get('/boards/{id}', ['uses' => 'BoardController@show']);
            $router->post('/boards', ['uses' => 'BoardController@store']);
        });

    });

});

