<?php

namespace App\Controllers\Api\V1\Churches;

use App\Controllers\BaseController;
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
    private ChurchModel $churchModel;
    private $user;

    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->churchService = Factories::class(ChurchService::class);
        $this->user = auth()->user();
        $this->churchModel = model(ChurchModel::class);
    }

    public function index(): string|false
    {
        $this->resposta->validate_request('get');
        $churches = $this->churchModel->getChurchesForUserAPI(withAddress: true);

        if ($churches === null) {
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

        $church = $this->churchModel->getByID(churchID: $id, withAddress: true, withImages: true);

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

            //$dadosIgreja = $this->validator->getValidated();

            $inputRequest = $this->request->getJSON(assoc: true);


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

            $dadosEndereco = $this->validator->getValidated();

            $addressID = model(AddressModel::class)->insert($dadosEndereco);

            if (!$addressID) {
                return $this->resposta->set_response_error(
                    status: 501,
                    message: 'error',
                    data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                    user_id: $this->user->id
                );
            }

            if ($addressID) {

                $igreja = [
                    'nome'       => $inputRequest['nome'],
                    'telefone'   => $inputRequest['telefone'],
                    'cnpj'       => $inputRequest['cnpj'],
                    'situacao'   => $inputRequest['situacao'],
                    'is_sede'    => $inputRequest['is_sede'],
                    'address_id' => $addressID
                ];


                $igrejaID = $this->churchModel->insert($igreja);

                if (!$igrejaID) {
                    return $this->resposta->set_response_error(
                        status: 501,
                        message: 'error',
                        data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                        user_id: $this->user->id
                    );
                }
            }

            $churchCreated = $this->churchModel->find($igrejaID);

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

        $church = $this->churchModel->getByID(churchID: $id, withAddress: true, withImages: false);

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

        $inputIgreja = $this->validator->getValidated();



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

        $inputEndereco = $this->validator->getValidated();

        model(AddressModel::class)->update($church->address_id, $inputEndereco);
        $success = $this->churchModel->update($church->id, $inputIgreja);


        //Se não foi salvo, retorna uma mensagem de erro
        if (!$success) {
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                user_id: $this->user->id
            );
        }

        $church = $this->churchModel->getByID(churchID: $church->id, withAddress: true);


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

        $church = $this->churchService->getByID(churchID: $id, withAddress: false, withImages: true);

        if ($church === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        $success = $this->churchService->destroy($church);
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
