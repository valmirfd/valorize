<?php

// define the namespace
namespace App\Routes;

// allows access to service routes

use App\Controllers\Api\V1\IgrejasController;
use App\Controllers\Api\V1\LoginController;
use App\Controllers\Api\V1\RegisterController;
use Config\Services;

// set the routes collection
$routes = Services::routes();



$routes->group('api', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {

    //Rotas para Registro
    $routes->post('register', [RegisterController::class, 'create']);

    //Rotas para Login
    $routes->post('login', [LoginController::class, 'create']);

    $routes->group('', ['filter' => 'jwt'], static function ($routes) {

        $routes->group('igrejas', static function ($routes) {
            $routes->get('list', [IgrejasController::class, 'index']);
            $routes->get('show/(:num)', [IgrejasController::class, 'show']);
            $routes->post('create', [IgrejasController::class, 'create']);
            $routes->put('update/(:num)', [IgrejasController::class, 'update']);
        });
    });
});
