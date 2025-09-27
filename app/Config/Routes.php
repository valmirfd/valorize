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

// Rotas para a API
if (file_exists($api = ROOTPATH . 'routes/api.php')) {

    require $api;
}


