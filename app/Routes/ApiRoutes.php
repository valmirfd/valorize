<?php

// define the namespace
namespace App\Routes;

// allows access to service routes

use App\Controllers\Api\V1\IgrejasController;

use Config\Services;

// set the routes collection
$routes = Services::routes();



$routes->group('api', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {

    $routes->group('igrejas', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
        $routes->get('list', [IgrejasController::class, 'index']);
    });
});
