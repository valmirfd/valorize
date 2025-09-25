<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Models\IgrejaModel;
use CodeIgniter\HTTP\ResponseInterface;

class IgrejasController extends BaseController
{
    private IgrejaModel $igrejaModel;

    public function __construct()
    {
        $this->igrejaModel = model(IgrejaModel::class);
    }

    public function index()
    {
        $data = [
            'title' => 'Igrejas da Região',
            'igrejas' => $this->igrejaModel->whereUser()->orderBy('id', 'DESC')->findAll()
        ];

        return view('Dashboard/Igrejas/index', $data);
    }
}
