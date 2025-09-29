<?php

namespace App\Controllers\Api\V1\Churches;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\ApiResponse;
use App\Services\ChurchService;
use App\Services\ImageService;
use App\Validations\ChurchImageValidation;
use CodeIgniter\Config\Factories;

class ChurchesImagesController extends BaseController
{
    private ApiResponse $resposta;
    private ChurchService $churchService;
    private $user;

    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->churchService = Factories::class(ChurchService::class);
        $this->user = auth()->user();
    }

    public function upload(int|null $id = null)
    {
        $this->resposta->validate_request('post');
        $data = [];


        $church = $this->churchService->getByID(churchID: $id, withAddress: true);

        if ($church === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        $rules = (new ChurchImageValidation)->getRules();
        if (!$this->validate($rules)) {
            $data[] = $this->validator->getErrors();

            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                data: $data,
                user_id: $this->user->id
            );
        }

        $this->churchService->salvarImagem($this->request->getFiles('images'), $church->id);

        $church = $this->churchService->getByID(churchID: $church->id, withAddress: false);

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $church,
            user_id: $this->user->id
        );
    }

    public function imageChurch(string|null $image = null, string $sizeImage = 'regular')
    {
        ImageService::showImage('churches', $image, $sizeImage);
    }

    public function deleteImageChurch(string|null $image = null)
    {
        $this->resposta->validate_request('delete');

        $result = $this->request->getJSON(assoc: true);

        $this->churchService->deleteImage($result['church_id'], $image);

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: [],
            user_id: $this->user->id
        );
    }
}
