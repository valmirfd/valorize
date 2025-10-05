<?php

namespace App\Controllers\Api\V1\Churches;

use App\Controllers\BaseController;
use App\Entities\Address;
use App\Entities\Church;
use App\Validations\AddressValidation;
use App\Validations\ChurchValidation;
use App\Libraries\ApiResponse;
use App\Models\AddressModel;
use App\Models\ChurchModel;
use App\Services\ChurchService;
use CodeIgniter\Config\Factories;


class ChurchesController extends BaseController
{

    private ApiResponse $resposta;


    private ChurchService $churchService;
    private $user;

    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->churchService = Factories::class(ChurchService::class);
        $this->user = auth()->user();
    }

    public function index(): string|false
    {
        $this->resposta->validate_request('get');
        $churches = $this->churchService->getChurchesForUserAPI(withAddress: true);

        if ($churches === []) {
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
            data: $churches,
            user_id: $this->user->id
        );
    }

    public function show($id = null): string|false
    {
        $this->resposta->validate_request('get');

        $data = [];

        $church = $this->churchService->getByID(churchID: $id, withAddress: true, withImages: true);

        if ($church === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        $data[] = $church;

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $data,
            user_id: $this->user->id
        );
    }

    public function create(): string|false
    {
        $this->resposta->validate_request('post');

        try {

            $data = [];

            //Valida os dados da Igreja vindos do post
            $rules = (new ChurchValidation)->getRules();
            if (!$this->validate($rules)) {
                $data[] = $this->validator->getErrors();

                return $this->resposta->set_response_error(
                    status: 404,
                    message: 'error',
                    data: $data,
                    user_id: $this->user->id
                );
            }

            $church = new Church($this->validator->getValidated());


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

            $success = $this->churchService->store(church: $church, address: $address);

            if (!$success) {
                return $this->resposta->set_response_error(
                    status: 501,
                    message: 'error',
                    data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                    user_id: $this->user->id
                );
            }


            $id = $this->churchService->getLastID();

            $churchCreated = $this->churchService->getByID(churchID: $id, withAddress: true, withImages: true);

            return $this->resposta->set_response(
                status: 200,
                message: 'success',
                data: $churchCreated,
                user_id: $this->user->id
            );
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {criação de church}', ['exception' => $e]);
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente. ', $e->getMessage()],
                user_id: $this->user->id
            );
        }
    }


    public function update($id = null)
    {
        $this->resposta->validate_request('put');
        $data = [];

        //$church = $this->churchService->getByID(churchID: $id, withAddress: true, withImages: false);
        $church = model(ChurchModel::class)->getByID(churchID: $id, withAddress: true, withImages: false);


        if ($church === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        $rules = (new ChurchValidation)->getRules($church->id);
        if (!$this->validate($rules)) {
            $data[] = $this->validator->getErrors();

            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                data: $data,
                user_id: $this->user->id
            );
        }

        $church->fill($this->validator->getValidated());

        $rules = (new AddressValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                data: $data,
                user_id: $this->user->id
            );
        }

        //Recuparamos o endereço associado
        $address = $church->address;
        $address->fill($this->validator->getValidated());

 
        $success = $this->churchService->store(church: $church, address: $address);


        //Se não foi salvo, retorna uma mensagem de erro
        if (!$success) {
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                user_id: $this->user->id
            );
        }

        $church = $this->churchService->getByID(churchID: $church->id, withAddress: true, withImages: true);


        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $church,
            user_id: $this->user->id
        );
    }


    public function destroy($id = null): string|false
    {
        $this->resposta->validate_request('delete');
        $data = [];

        $church = $this->churchService->getByID(churchID: $id, withAddress: true, withImages: true);

        if ($church === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        $success = model(ChurchModel::class)->destroy($church);
        if (!$success) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                data: ['info' => 'Opss! Aconteceu um erro na exclusão tente novamente'],
                user_id: $this->user->id
            );
        }

        $data[] = $church;

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $data,
            user_id: $this->user->id
        );
    }
}
