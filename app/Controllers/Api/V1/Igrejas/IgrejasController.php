<?php

namespace App\Controllers\Api\V1\Igrejas;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ApiResponse;
use App\Services\IgrejaService;
use CodeIgniter\Config\Factories;

class IgrejasController extends BaseController
{
    private ApiResponse $resposta;
    private IgrejaService $igrejaService;
    private $user;


    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->igrejaService = Factories::class(IgrejaService::class);
        $this->user = auth()->user();
    }


    public function index(): string
    {
        $this->resposta->validate_request('get');

        $igrejas = $this->igrejaService->listarIgrejas();

        if ($igrejas === []) {
            return $this->resposta->set_response(
                status: 200,
                message: 'success',
                data: ['info' => 'Nenhuma Igreja foi cadastrada ainda'],
                user_id: $this->user->id
            );
        }

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $igrejas,
            user_id: $this->user->id
        );
    }
}
