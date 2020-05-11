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

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->group(['prefix' => 'auth'], function () use ($router) {

        $router->post('register', [
            'as' => 'auth.register',
            'uses' => 'AuthController@register'
        ]);
        $router->post('login', [
            'as' => 'auth.login',
            'uses' => 'AuthController@login'
        ]);

    });

    $router->group(['prefix' => 'users', 'middleware' => ['auth:api']], function () use ($router) {

        $router->get('/', function () {
            return 'get users';
        });
    });
});
