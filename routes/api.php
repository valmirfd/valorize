<?php

use App\Controllers\Api\V1\IgrejasController;
use App\Controllers\Api\V1\LoginController;
use App\Controllers\Api\V1\RegisterController;

$routes->group('api', ['namespace' => 'App\Controllers\API\V1'], static function ($routes) {

    //Rotas para Registro
    $routes->post('register', [RegisterController::class, 'create']);
    $routes->options('register', static function () {});
    $routes->options('register/(:any)', static function () {});

    //Rotas para Login
    $routes->post('login', [LoginController::class, 'create']);
    $routes->options('login', static function () {});
    $routes->options('login/(:any)', static function () {});

    $routes->group('', ['filter' => 'jwt'], static function ($routes) {

        $routes->group('igrejas', ['namespace' => 'App\Controllers\API\V1'], static function ($routes) {
            $routes->get('list', [IgrejasController::class, 'index']);
            $routes->get('show/(:num)', [IgrejasController::class, 'show']);
            $routes->post('create', [IgrejasController::class, 'create']);
            $routes->put('update/(:num)', [IgrejasController::class, 'update']);
            $routes->delete('destroy/(:num)', [IgrejasController::class, 'destroy']);
        });
    });
});
