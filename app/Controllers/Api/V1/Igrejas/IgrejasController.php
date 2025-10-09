<?php

namespace App\Controllers\Api\V1\Igrejas;

use App\Controllers\BaseController;
use App\Entities\Address;
use App\Entities\Igreja;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ApiResponse;
use App\Services\IgrejaService;
use App\Validations\AddressValidation;
use App\Validations\IgrejaValidation;
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


    //Lista todas as Igrejas do user logado
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

    //Busca apenas uma Igreja do user logado
    public function show($igrejaID = null): string
    {
        $this->resposta->validate_request('get');

        $data = [];

        $igreja = $this->igrejaService->showIgreja(igrejaID: $igrejaID, withAddress: true, withImages: true);

        if ($igreja === []) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        $data[] = $igreja;

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $data,
            user_id: $this->user->id
        );
    }

    //Cria uma igreja para o user logado
    public function create(): string|false
    {
        $this->resposta->validate_request('post');

        try {

            $data = [];

            //Valida os dados da Igreja vindos do post
            $rules = (new IgrejaValidation)->getRules();
            if (!$this->validate($rules)) {
                $data[] = $this->validator->getErrors();

                return $this->resposta->set_response_error(
                    status: 404,
                    message: 'error',
                    data: $data,
                    user_id: $this->user->id
                );
            }

            $igreja = new Igreja($this->validator->getValidated());


            //Valida os dados de endereço vindos do post
            $rules = (new AddressValidation)->getRules();
            if (!$this->validate($rules)) {
                $data[] = $this->validator->getErrors();

                return $this->resposta->set_response_error(
                    status: 404,
                    message: 'error',
                    data: $data,
                    user_id: $this->user->id
                );
            }

            //instanciamos o endereço com os dados validados
            $address = new Address($this->validator->getValidated());

            $success = $this->igrejaService->store(igreja: $igreja, address: $address);

            if (!$success) {
                return $this->resposta->set_response_error(
                    status: 501,
                    message: 'error',
                    data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                    user_id: $this->user->id
                );
            }


            $id = $this->igrejaService->getLastID();

            $igrejaCreated = [];

            $igrejaCreated[] = $this->igrejaService->showIgreja(igrejaID: $id, withAddress: true, withImages: true);

            return $this->resposta->set_response(
                status: 200,
                message: 'success',
                data: $igrejaCreated,
                user_id: $this->user->id
            );
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {criação de Igreja}', ['exception' => $e]);
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente. ', $e->getMessage()],
                user_id: $this->user->id
            );
        }
    }
}
