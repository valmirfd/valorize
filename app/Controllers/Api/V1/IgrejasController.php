<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Services\IgrejaService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Config\Factories;

class IgrejasController extends BaseController
{
    use ResponseTrait;

    private $igrejaService;

    public function __construct()
    {
        $this->igrejaService = Factories::class(IgrejaService::class);
    }

    public function index()
    {
        $data = $this->igrejaService->buscarIgrejasForUserAPI();


        return $this->respond(data: $data, status: 200, message: 'success');
    }
}
