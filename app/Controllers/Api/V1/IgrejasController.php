<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Entities\Address;
use App\Entities\Igreja;
use App\Libraries\ApiResponse;
use App\Models\IgrejaModel;
use App\Validation\AddressValidation;
use App\Validation\IgrejaValidation;
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

    /**
     * Retorna uma Igreja de acordo com o ID passado ou null
     *
     * @param integer $igrejaID
     * @return string
     */
    public function show(int $igrejaID): string
    {
        $igreja =  $this->igrejaModel->getByID(igrejaID: $igrejaID, withAddress: true);

        if ($igreja === null) {
            return $this->resposta->set_response_error(status: 404, message: 'Igreja não encontrada');
        }

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $igreja
        );
    }

    public function create()
    {
        $rules = (new IgrejaValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                errors: $this->validator->getErrors()
            );
        }

        $rules = (new AddressValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                errors: $this->validator->getErrors()
            );
        }


        //instanciamos o endereço com os dados validados
        $address = new Address($this->validator->getValidated());

        //instanciamos a Igreja com os dados validados
        $igreja = new Igreja($this->validator->getValidated());

        $success = $this->igrejaModel->store(igreja: $igreja, address: $address);

        if (!$success) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'Erro ao criar Igreja',
                errors: []
            );
        }

        $igrejaID = $this->igrejaModel->getInsertID();

        $igreja = $this->igrejaModel->getByID($igrejaID);

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $igreja
        );
    }
}
