<?php

namespace App\Controllers\Manager;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ManagerController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home'
        ];

        return view('Manager/Home/index', $data);
    }
}
