<?php

// define the namespace
namespace App\Routes;

// allows access to service routes

use App\Controllers\Dashboard\DashboardController;
use Config\Services;

// set the routes collection
$routes = Services::routes();



$routes->group('dashboard', ['namespace' => 'App\Controllers\Dashboard'], function ($routes) {

    $routes->get('/', [DashboardController::class, 'index'], ['as' => 'dashboard.home']);
});
