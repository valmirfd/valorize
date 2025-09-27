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

    public function index(): ResponseInterface
    {
        $data = $this->igrejaService->buscarIgrejasForUserAPI();

        return $this->respond(data: $data, status: 200, message: 'success');
    }

    public function create(): ResponseInterface
    {

        //Valida os dados da Igreja vindos no post
        $rules = (new IgrejaValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->respond(data: $this->validator->getErrors(), status: 401, message: 'error');
        }

        //Recebe apenas os dados que foram validados no post
        $igreja = new Igreja($this->validator->getValidated());

        //Este array vazio é para receber os dados retornados e devolver no formato de array de objetos
        $data = [];

        //Valida os dados de endereço vindos do post
        $rules = (new AddressValidation)->getRules();
        if (!$this->validate($rules)) {
            return $this->respond(data: $this->validator->getErrors(), status: 401, message: 'error');
        }

        //instanciamos o endereço com os dados validados
        $address = new Address($this->validator->getValidated());

        // Envia os dados para a classe de Serviço e Recebe tue ou false
        $success = $this->igrejaService->store(igreja: $igreja, address: $address);

        //Se não foi salvo os dados retorna uma mensagem de erro
        if (!$success) {
            return $this->respond(data: [], status: 401, message: 'error');
        }

        //Busca o último ID que foi criado na tabela igrejas
        $id = model(IgrejaModel::class)->getInsertID();
        //Busca a igreja pelo ID
        $igrejaCriada = $this->igrejaService->getByID($id);

        //Recebe a última igreja criada
        $data[] = $igrejaCriada;

        //Retorna a última Igreja criada
        return $this->respondCreated(data: $data, message: 'success');
    }

    public function show(int|null $igrejaID = null): ResponseInterface
    {
        $igreja = $this->igrejaService->getByID($igrejaID);
        $data = [];
        if ($igreja === null) {
            return $this->failNotFound(description: 'Igreja não encotrada', code: ResponseInterface::HTTP_NOT_FOUND);
        }

        $data[] = $igreja;

        return $this->respond(data: $data, status: ResponseInterface::HTTP_OK);
    }

    public function update(int|null $igrejaID = null)
    {
        
    }
}
