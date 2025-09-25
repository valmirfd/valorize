<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ApiResponse;
use App\Models\IgrejaModel;
use CodeIgniter\Config\Factories;

class IgrejasController extends BaseController
{
    private ApiResponse $resposta;
    private IgrejaModel $igrejaModel;

    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->igrejaModel = model(IgrejaModel::class);
    }

    public function index()
    {
        $this->resposta->validate_request('get');
        $superID = auth()->user()->superintendente_id;

        $igrejas = $this->igrejaModel->buscarIgrejasForUserAPI(superID: $superID,);

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $igrejas,
        );
    }
}
