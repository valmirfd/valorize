<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Entities\Address;
use App\Entities\Igreja;
use App\Requests\IgrejaRequest;
use App\Services\IgrejaService;
use App\Validations\AddressValidation;
use App\Validations\IgrejaValidation;
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

    public function create()
    {

        $rules = (new IgrejaValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->respond(data: $this->validator->getErrors(), status: 401, message: 'error');
        }

        $igreja = new Igreja($this->validator->getValidated());

        $rules = (new AddressValidation)->getRules();

        if (!$this->validate($rules)) {
            return $this->respond(data: $this->validator->getErrors(), status: 401, message: 'error');
        }

        //instanciamos o endereço com os dados validados
        $address = new Address($this->validator->getValidated());


        /*$success = $this->igrejaService->store(parent: $parent, address: $address);

        if (!$success) {
            return redirect()->back()->with('danger', 'Ocorreu um erro na criação do responsável!');
        }*/
    }
}
