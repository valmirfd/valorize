<?php

// define the namespace
namespace App\Routes;

// allows access to service routes

use App\Controllers\Dashboard\DashboardController;
use App\Controllers\Dashboard\IgrejasController;
use Config\Services;

// set the routes collection
$routes = Services::routes();



$routes->group('dashboard', ['namespace' => 'App\Controllers\Dashboard'], function ($routes) {

    $routes->get('/', [DashboardController::class, 'index'], ['as' => 'dashboard.home']);

    // Igrejas
    $routes->group('igrejas', function ($routes) {
        $routes->get('/', [IgrejasController::class, 'index'], ['as' => 'dashboard.igrejas']);

        /*$routes->get('buscar-igrejas', 'IgrejasController::buscarIgrejas', ['as' => 'buscar.igrejas']);
        $routes->get('exibir-info-igreja', 'IgrejasController::exibirInfoIgreja', ['as' => 'exibir.info.igreja']);
        $routes->get('busca-categorias-situacoes', 'IgrejasController::buscaCategoriasSituacoes', ['as' => 'busca.categorias.situacoes']);
        $routes->post('criar', 'IgrejasController::criar', ['as' => 'criar.igreja', 'filter' => 'igrejas']);
        $routes->put('editar', 'IgrejasController::editar', ['as' => 'editar.igreja']);
        $routes->put('arquivar', 'IgrejasController::arquivar', ['as' => 'arquivar.igreja']);
        $routes->get('arquivadas', 'IgrejasController::arquivadas', ['as' => 'igrejas.arquivadas']);
        $routes->get('buscar-grupos-arquivados', 'IgrejasController::buscarIgrejasArquivadas', ['as' => 'buscar.igrejas.arquivadas']);
        $routes->put('recuperar', 'IgrejasController::recuperar', ['as' => 'recuperar.igreja']);
        $routes->delete('excluir', 'IgrejasController::excluir', ['as' => 'excluir.igreja']);

        $routes->get('detalhes/(:any)', 'IgrejasController::detalhes/$1', ['as' => 'detalhes.igreja']);

        //Imagem Igreja
        $routes->get('editar-imagem-igreja/(:num)', 'IgrejasController::editarImagemIgreja/$1', ['as' => 'editar.imagem.igreja']);
        $routes->put('upload-igreja/(:num)', 'IgrejasController::uploadIgreja/$1', ['as' => 'upload.igreja']);
        $routes->get('imagem-igreja/(:any)/(:any)', 'IgrejasController::imagemIgreja/$1/$2', ['as' => 'imagem.igreja']);
        $routes->delete('excluir-imagem-igreja/(:any)', 'IgrejasController::excluirImagemIgreja/$1', ['as' => 'excluir.imagem.igreja']);*/
    });
});
