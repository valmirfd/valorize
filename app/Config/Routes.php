<?php

use App\Controllers\Api\V1\ChurchesController;
use App\Controllers\Api\V1\LoginController;
use App\Controllers\Api\V1\RegisterController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);



$routes->group('api', ['namespace' => ''], static function ($routes) {

    //Rotas para Registro
    $routes->post('register', [RegisterController::class, 'create']);
    $routes->options('register', static function () {});
    $routes->options('register/(:any)', static function () {});

    //Rotas para Login
    $routes->post('login', [LoginController::class, 'create']);
    $routes->options('login', static function () {});
    $routes->options('login/(:any)', static function () {});

    $routes->group('', ['filter' => 'jwt'], static function ($routes) {
        //Rotas para Empresas
        $routes->resource('churches', ['controller' => ChurchesController::class, 'except' => 'new,edit']);
        $routes->options('churches', static function () {});
        $routes->options('churches/(:any)', static function () {});
    });
});
