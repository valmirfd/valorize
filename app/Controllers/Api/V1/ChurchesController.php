<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use App\Entities\Address;
use App\Entities\Church;
use App\Models\ChurchModel;
use App\Validations\AddressValidation;
use App\Validations\IgrejaValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

class ChurchesController extends BaseController
{
    use ResponseTrait;

    private $churchModel;
    private $user;

    public function __construct()
    {
        $this->churchModel = model(ChurchModel::class);
        $this->user = auth()->user();
    }

    public function index()
    {
        $data = $this->churchModel->buscarIgrejasForUserAPI(withAddress: true);

        return $this->respond(data: $data, status: 200, message: 'success');
    }

    public function show($id = null)
    {
        $data = [];

        $data[] = $this->churchModel->getByID(churchID: $id, withAddress: true);

        if ($data === null) {
            return $this->failNotFound(code: ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->respond(data: $data, status: ResponseInterface::HTTP_OK);
    }

    public function create()
    {
        $rules = (new IgrejaValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $church = new Church($this->validator->getValidated());

        //Valida os dados de endereço vindos do post
        $rules = (new AddressValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        //instanciamos o endereço com os dados validados
        $address = new Address($this->validator->getValidated());

        $success = $this->churchModel->store(church: $church, address: $address);

        //Se não foi salvo, retorna uma mensagem de erro
        if (!$success) {
            return $this->respond(data: ['info' => 'Erro ao salvar Church'], status: 401, message: 'error');
        }

        $data = [];

        $id = $this->churchModel->getInsertID();

        $data[] = $this->churchModel->getByID(churchID: $id, withAddress: true);


        return $this->respondCreated(data: $data);
    }
}
