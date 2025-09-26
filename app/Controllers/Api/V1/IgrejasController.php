<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Libraries\ApiResponse;
use App\Models\IgrejaModel;
use CodeIgniter\Config\Factories;
use CodeIgniter\Format\JSONFormatter;
use CodeIgniter\HTTP\Response;
use PHPUnit\Util\Json;

class IgrejasController extends BaseController
{
    private ApiResponse $resposta;
    private IgrejaModel $igrejaModel;

    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->igrejaModel = model(IgrejaModel::class);
    }


    /**
     * Método responsável em retornar uma string no formato json
     * com todas as Igrejas do usuário logado
     *
     * @return string
     */
    public function index(): string
    {
        $this->resposta->validate_request('get');

        $igrejas = $this->igrejaModel->asObject()->buscarIgrejasForUserAPI(userID: auth()->user()->id);

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $igrejas
        );
    }
}
