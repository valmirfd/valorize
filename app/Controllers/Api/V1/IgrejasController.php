<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Entities\Address;
use App\Entities\Igreja;
use App\Models\IgrejaModel;
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

        $data = [];

        $rules = (new AddressValidation)->getRules();

        if (!$this->validate($rules)) {
            return $this->respond(data: $this->validator->getErrors(), status: 401, message: 'error');
        }

        //instanciamos o endereço com os dados validados
        $address = new Address($this->validator->getValidated());

        $success = $this->igrejaService->store(igreja: $igreja, address: $address);

        if (!$success) {
            return $this->respond(data: [], status: 401, message: 'error');
        }

        $id = model(IgrejaModel::class)->getInsertID();
        $igrejaCriada = $this->igrejaService->getByID($id);

        $data[] = $igrejaCriada;

        return $this->respondCreated(data: $data, message: 'success');
    }

    public function get($igrejaID)
    {
        $igreja = $this->igrejaService->getByID($igrejaID);
        $data = [];
        if ($igreja === null) {
            return $this->respond(data: ['info' => 'Não foi possível encontrar os dados'], status: 401, message: 'error');
        }

        $data[] = $igreja;

        return $this->respondCreated(data: $data, message: 'success');
    }
}
