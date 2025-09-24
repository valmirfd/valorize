<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);


require APPPATH . 'Routes/ManagerRoutes.php';
require APPPATH . 'Routes/DashboardRoutes.php';