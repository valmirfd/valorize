<?php

// define the namespace
namespace App\Routes;

// allows access to service routes

use App\Controllers\Manager\ManagerController;
use Config\Services;

// set the routes collection
$routes = Services::routes();



$routes->group('manager', ['namespace' => 'App\Controllers\Manager', 'filter' => 'superadmin'], function ($routes) {

    $routes->get('/', [ManagerController::class, 'index'], ['as' => 'manager.home']);
});
