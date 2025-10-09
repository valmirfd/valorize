<?php

namespace App\Controllers\Api\V1\Igrejas;

use App\Controllers\BaseController;
use App\Libraries\ApiResponse;
use App\Services\IgrejaService;
use App\Services\ImageService;
use App\Validations\IgrejaImageValidation;
use CodeIgniter\Config\Factories;

class IgrejasImagesController extends BaseController
{
    private ApiResponse $resposta;
    private IgrejaService $igrejaService;
    private $user;


    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->igrejaService = Factories::class(IgrejaService::class);
        $this->user = auth()->user();
    }

    public function upload(int|null $igrejaID = null)
    {
        $this->resposta->validate_request('post');
        $data = [];

        $igreja = $this->igrejaService->getByID(igrejaID: $igrejaID, withAddress: false, withImages: true);


        if ($igreja === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }


        $rules = (new IgrejaImageValidation)->getRules();
        if (!$this->validate($rules)) {
            $data[] = $this->validator->getErrors();

            return $this->resposta->set_response_error(
                status: 404,
                message: 'error',
                data: $data,
                user_id: $this->user->id
            );
        }

        $images = $this->request->getFiles('images');

        $this->igrejaService->salvarImagem(images: $images, igrejaID: $igreja->id);

        $data = $this->igrejaService->showIgreja(igrejaID: $igreja->id, withAddress: true, withImages: true);

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $data,
            user_id: $this->user->id
        );
    }

    public function imageIgreja(string|null $image = null)
    {

        ImageService::showImage(imagePath: 'igrejas', image: $image, sizeImage: 'regular');
    }

    public function deleteImageIgreja(int|null $igrejaID = null)
    {
        $this->resposta->validate_request('delete');

        $igreja = $this->igrejaService->getByID(igrejaID: $igrejaID, withAddress: false, withImages: true);

        if ($igreja === null) {
            return $this->resposta->set_response_error(
                status: 404,
                message: 'not found',
                data: ['info' => 'Não há dados para exibir'],
                user_id: $this->user->id
            );
        }

        //Recebe o nome da image (name_image)
        $result = $this->request->getJSON(assoc: true);

        $nameImage = $result['name_image'];

        $success = $this->igrejaService->deleteImage(igrejaID: $igreja->id, image: $nameImage);

        if ($success) {
            ImageService::destroyImage('igrejas', $result['name_image']);
        }

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: ['info' => 'Imagem deletada com sucesso'],
            user_id: $this->user->id
        );
    }
}
