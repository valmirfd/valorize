<?php

use App\Controllers\Api\V1\Churches\ChurchesController;
use App\Controllers\Api\V1\Churches\ChurchesImagesController;
use App\Controllers\Api\V1\LoginController;
use App\Controllers\Api\V1\RegisterController;
use App\Controllers\Api\V1\UserController;

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

        $routes->group('users', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
            $routes->get('user', [UserController::class, 'index']);
            $routes->options('', static function () {});
            $routes->options('(:any)', static function () {});
        });

        $routes->group('churches', ['namespace' => 'App\Controllers\Api\V1\Churches'], static function ($routes) {
            $routes->get('list', [ChurchesController::class, 'index']);
            $routes->get('show/(:num)', [ChurchesController::class, 'show']);
            $routes->post('create', [ChurchesController::class, 'create']);
            $routes->put('update/(:num)', [ChurchesController::class, 'update']);
            $routes->delete('destroy/(:num)', [ChurchesController::class, 'destroy']);
            $routes->options('', static function () {});
            $routes->options('(:any)', static function () {});
            //Images
            $routes->get('image-church/(:any)/(:any)', [ChurchesImagesController::class, 'imageChurch']);
            $routes->post('upload/(:num)', [ChurchesImagesController::class, 'upload']);
            $routes->delete('destroy-image/(:num)', [ChurchesImagesController::class, 'deleteImageChurch']);
            //$routes->options('upload', static function () {});
            $routes->options('upload/(:any)', static function () {});
            $routes->options('image-church/(:any)/(:any)', static function () {});
        });
    });
});
