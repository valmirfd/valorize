<?php

namespace App\Controllers\Api\V1;

use App\Models\ChurchModel;
use App\Validation\ChurchValidation;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ChurchesController extends ResourceController
{
    protected $modelName = ChurchModel::class;
    protected $format = 'json';

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $churches = $this->model->whereUser()->orderBy('id', 'DESC')->findAll();

        if ($churches === []) {

            return $this->respond(data: ['message' => 'Ainda não há Igrejas cadastradas!'], status: 200, message: 'success');
        }

        return $this->respond(data: $churches, status: 200, message: 'success');
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $church = $this->model->whereUser()->asObject()->find($id);

        if ($church === null) {

            return $this->failNotFound(code: ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->respond(data: $church, status: ResponseInterface::HTTP_OK);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = (new ChurchValidation)->getRules();

        if (!$this->validate($rules)) {

            return $this->failValidationErrors($this->validator->getErrors());
        }

        $inputRequest = esc($this->request->getJSON(assoc: true));

        $id = $this->model->insert($inputRequest);

        $churchCreated = $this->model->find($id);

        return $this->respondCreated(data: $churchCreated);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //Busca a Igreja do user logado
        $church = $this->model->whereUser()->find($id);

        //Tentou editar uma Igreja que não é dele
        if ($church === null) {
            return $this->failNotFound(code: ResponseInterface::HTTP_NOT_FOUND);
        }

        $rules = (new ChurchValidation)->getRules($id);

        if (!$this->validate($rules)) {

            return $this->failValidationErrors($this->validator->getErrors());
        }

        $inputRequest = $this->request->getJSON(assoc: true);

        $this->model->whereUser()->update($id, $inputRequest);

        $church = $this->model->find($id);

        return $this->respondUpdated(data: $church);
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //Busca a Igreja do user logado
        $church = $this->model->whereUser()->find($id);

        //Tentou excluir uma Igreja que não é dele
        if ($church === null) {
            return $this->failNotFound(code: ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->model->delete($id);

        return $this->respondDeleted(data: $church, message: 'success');
    }
}
