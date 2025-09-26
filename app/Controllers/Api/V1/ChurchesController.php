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
    public function index(): ResponseInterface
    {

        return $this->respond($this->model->whereUser()->orderBy('id', 'DESC')->findAll());
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

        //$company->routes = model(RouteModel::class)->where('company_id', $company->id)->findAll();

        return $this->respond(data: $church, status: ResponseInterface::HTTP_OK);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
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
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
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
        $rules = (new ChurchValidation)->getRules($id);

        if (!$this->validate($rules)) {

            return $this->failValidationErrors($this->validator->getErrors());
        }

        $inputRequest = esc($this->request->getJSON(assoc: true));

        $this->model->update($id, $inputRequest);

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
        //
    }
}
