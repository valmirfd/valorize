<?php

namespace App\Controllers\Api\V1;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ApiResponse;
use CodeIgniter\Config\Factories;

class UserController extends BaseController
{
    private ApiResponse $resposta;

    private $user;

    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->user = auth()->user();
    }

    public function index(): string|false
    {
        $this->resposta->validate_request('get');

        $this->user->roles = $this->user->getGroups();

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $this->user,
            user_id: $this->user->id
        );
    }
}
