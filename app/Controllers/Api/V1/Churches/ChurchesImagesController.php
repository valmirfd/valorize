<?php

namespace App\Controllers\Api\V1\Churches;

use App\Controllers\BaseController;
use App\Libraries\ApiResponse;
use App\Models\ChurchModel;
use App\Services\ChurchService;
use App\Services\ImageService;
use App\Validations\ChurchImageValidation;
use CodeIgniter\Config\Factories;

class ChurchesImagesController extends BaseController
{
    private ApiResponse $resposta;
    private ChurchModel $churchModel;
    private ChurchService $churchService;
    private $user;


    public function __construct()
    {
        $this->resposta = Factories::class(ApiResponse::class);
        $this->churchService = Factories::class(ChurchService::class);
        $this->churchModel = model(ChurchModel::class);
        $this->user = auth()->user();
    }

    public function upload(int|null $id = null)
    {
        $this->resposta->validate_request('post');
        $data = [];

        $church = $this->churchModel->getByID(churchID: $id, withAddress: false, withImages: false);

        if ($church === []) {
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

        $images = $this->request->getFiles('images');

        $this->churchService->salvarImagem(images: $images, churchID: $id);

        $data = $this->churchService->getByID(churchID: $id, withAddress: true, withImages: true);



        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: $data,
            user_id: $this->user->id
        );
    }

    public function imageChurch(string|null $image = null)
    {

        ImageService::showImage(imagePath: 'churches', image: $image, sizeImage: 'regular');
    }

    public function deleteImageChurch(int|null $id = null)
    {
        $this->resposta->validate_request('delete');

        $church = $this->churchModel->getByID(churchID: $id, withAddress: false);
        if ($church === null) {
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

        $success = $this->churchModel->deleteImage($church->id, $nameImage);

        if ($success) {
            ImageService::destroyImage('churches', $result['name_image']);
        }

        return $this->resposta->set_response(
            status: 200,
            message: 'success',
            data: ['info' => 'Imagem deletada com sucesso'],
            user_id: $this->user->id
        );
    }
}
