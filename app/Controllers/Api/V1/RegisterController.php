<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Events\Events;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegisterController;
use CodeIgniter\Shield\Exceptions\ValidationException;

class RegisterController extends ShieldRegisterController
{
    use ResponseTrait;

    public function create(): ResponseInterface
    {
        $userModel = $this->getUserProvider();

        $rules = $this->getValidationRules();

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $user = $this->getUserEntity();

        $user->fill($this->validator->getValidated());

        try {

            $userModel->save($user);
        } catch (ValidationException $e) {
            return $this->failValidationErrors($userModel->errors());
        }

        $user = $userModel->findById($userModel->getInsertID());

        $userModel->addToDefaultGroup($user);

        Events::trigger('register', $user);

        $user->activate();

        $manager = service('jwtmanager');

        $jwt = $manager->generateToken($user);

        $user->superintendente_id = $user->id;
        $userModel->save($user);


        return $this->respondCreated([
            'token' => $jwt,
            'id' => $user->id,
            'username' => $user->username,
            'is_superintendente' => $user->is_superintendente,
            'superintendente_id' => $user->superintendente_id,
        ]);
    }
}
