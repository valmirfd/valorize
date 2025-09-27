<?php

use App\Controllers\Api\V1\IgrejasController;

$routes->group('api', ['namespace' => 'App\Controllers\API\V1'], static function ($routes) {

    $routes->group('', ['filter' => 'jwt'], static function ($routes) {

        $routes->group('igrejas', ['namespace' => 'App\Controllers\API\V1'], static function ($routes) {
            $routes->get('list', [IgrejasController::class, 'index']);
            $routes->get('get/(:num)', [IgrejasController::class, 'getIgreja']);
            $routes->delete('destroy/(:num)', [IgrejasController::class, 'deletarIgreja']);
            $routes->put('update/(:num)', [IgrejasController::class, 'edtarIgreja']);
            $routes->post('create', [IgrejasController::class, 'criarIgreja']);
        });
    });

});
