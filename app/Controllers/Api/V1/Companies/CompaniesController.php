<?php

namespace App\Controllers\Api\V1\Companies;

use App\Controllers\BaseController;
use App\Entities\Company;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ApiResponse;
use App\Models\CompanyModel;
use App\Services\CompanyService;
use App\Validations\CompanyValidation;
use CodeIgniter\Config\Factories;

class CompaniesController extends BaseController
{

    private ApiResponse $resposta;
    private CompanyService $companyService;
    private $user;


    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->companyService = Factories::class(CompanyService::class);
        $this->user = auth()->user();
    }

    public function index()
    {
        $this->resposta->validate_request('get');

        $companies = $this->companyService->listarCompanies();

        if ($companies === null || $companies === []) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $companies,
            user_id: $this->user->id
        );
    }

    public function show(int $companyID)
    {
        $company = $this->companyService->getByID($companyID);

        if ($company === null || $company === []) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $company,
            user_id: $this->user->id
        );
    }

    public function create()
    {
        $this->resposta->validate_request('post');

        try {

            $rules = (new CompanyValidation)->getRules();

            if (!$this->validate($rules)) {
                $data[] = $this->validator->getErrors();

                return $this->resposta->set_response_error(
                    status: 404,
                    message: 'error',
                    data: $data,
                    user_id: $this->user->id
                );
            }

            $company = new Company($this->validator->getValidated());

            $id = $this->companyService->criarCompany($company);

            if (!$id) {
                return $this->resposta->set_response_error(
                    status: 501,
                    message: 'error',
                    data: ['info' => 'Opss! Algo deu errado tente novamente.'],
                    user_id: $this->user->id
                );
            }

            $newCompany = $this->companyService->getByID($id);


            return $this->resposta->set_response(
                status: 200,
                message: 'success',
                data: $newCompany,
                user_id: $this->user->id
            );
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {criação de Company}', ['exception' => $e]);
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente. ', $e->getMessage()],
                user_id: $this->user->id
            );
        }
    }

    public function update(int $companyID)
    {
        $this->resposta->validate_request('put');

        $company = $this->companyService->getByID($companyID);
        if ($company === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        try {

            $rules = (new CompanyValidation)->getRules($companyID);

            if (!$this->validate($rules)) {
                $data[] = $this->validator->getErrors();

                return $this->resposta->set_response_error(
                    status: 404,
                    message: 'error',
                    data: $data,
                    user_id: $this->user->id
                );
            }

            $inputRequest = $this->validator->getValidated();

            $this->companyService->editarCompany($companyID, $inputRequest);

            $editedCompany = $this->companyService->getByID($companyID);


            return $this->resposta->set_response(
                status: 200,
                message: 'success',
                data: $editedCompany,
                user_id: $this->user->id
            );
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {Edição de Company}', ['exception' => $e]);
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente. ', $e->getMessage()],
                user_id: $this->user->id
            );
        }
    }

    public function destroy(int $companyID)
    {
        $company = $this->companyService->getByID($companyID);

        if ($company === null || $company === []) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        try {

            $success = $this->companyService->deleteCompany($companyID);

            if (!$success) {
                return $this->resposta->set_response_error(
                    status: 404,
                    message: 'not found',
                    data: ['info' => 'Não há dados para exibir'],
                    user_id: $this->user->id
                );
            }

            return $this->resposta->set_response(
                status: 200,
                message: 'success',
                data: $company,
                user_id: $this->user->id
            );
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {Exclusão de Company}', ['exception' => $e]);
            return $this->resposta->set_response_error(
                status: 501,
                message: 'error',
                data: ['info' => 'Opss! Algo deu errado tente novamente. ', $e->getMessage()],
                user_id: $this->user->id
            );
        }
    }
}
