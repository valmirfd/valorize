<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class IgrejasController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Igrejas da Região'
        ];

        return view('Dashboard/Igrejas/index', $data);
    }
}
