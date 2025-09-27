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
        $user = auth()->user();

        echo '<pre>';
        print_r($user);
        exit;

        /*$perPage = $this->request->getGet('perPage');
        $page = $this->request->getGet('page');

        $igrejas = (object) $this->igrejaService->buscarIgrejasForUserAPI(perPage: $perPage, page: $page);
        $pager = $igrejas->pager;

        return $this->respond(
            [
                'code' => 200,
                'igrejas' => $igrejas->igrejas,
                'pager' => $pager
            ]
        );*/
    }
}
