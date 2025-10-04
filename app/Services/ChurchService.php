<?php

namespace App\Services;

use App\Entities\Address;
use App\Entities\Church;
use App\Models\ChurchModel;
use CodeIgniter\Config\Factories;

class ChurchService
{
    private ChurchModel $churchModel;


    public function __construct()
    {
        $this->churchModel = model(ChurchModel::class);
    }

    /**
     * Método responsável em buscar todas as churches de acordo com o superintendente_id do usuário logado
     *
     * @param boolean $withAddress
     * @return array|null
     */
    public function getChurchesForUserAPI(bool $withAddress = false): array|null
    {

        $churches = $this->churchModel->asObject()->getChurchesForUserAPI(withAddress: $withAddress);

        return $churches;
    }

    /**
     * Retorna uma única Church de acordo com o ID informado
     *
     * @param string|null $churchID
     * @param boolean $withAddress
     * @param boolean $withImages
     * @return array|object|null
     */
    public function getByID(
        string|null $churchID,
        bool $withAddress = false,
        bool $withImages = false
    ): array|object|null {

        $church = $this->churchModel->getByID(churchID: $churchID, withAddress: $withAddress, withImages: $withImages);

        $data = [
            "id" => $church->id,
            "user_id" => $church->id,
            "address_id" => $church->user_id,
            "nome" => $church->nome,
            "telefone" => $church->telefone,
            "cnpj" => $church->cnpj,
            "code" => $church->code,
            "situacao" => $church->situacao,
            "superintendente_id" => $church->superintendente_id,
            "titular_id" => $church->titular_id,
            "is_sede" => $church->is_sede,
            "ativo" => $church->ativo,
            "address" => $church->address->getFullAddress(),
            "images" => $church->image(),
            "created_at" => $church->created_at,
            "updated_at" => $church->updated_at,
            "deleted_at" => $church->deleted_at,
        ];

       


        if (is_null($church)) {

            return null;
        }

        return $data;
    }

    /**
     * Retorna o último ID inserido na tabela churches
     *
     * @return integer
     */
    public function getLastID(): int
    {
        return $this->churchModel->getLastID();
    }

    public function addChurch() {}

    /**
     * Método responsável tanto para salvar uma Church com para editar
     *
     * @param Church $church
     * @param Address $address
     * @return boolean
     */
    public function store(Church $church, Address $address): bool
    {
        return $this->churchModel->store(church: $church, address: $address);
    }

    /**
     * Método responsável em excluir uma Church do banco de dados
     *
     * @param Church $church
     * @return boolean
     */
    public function destroy(Church $church): bool
    {

        $images = $church->images;

        if ($this->churchModel->destroy(church: $church)) {

            if ($images !== null) {
                foreach ($images as $image) {
                    $data = $image->image;
                    ImageService::destroyImage('churches', $data);
                }

                return true;
            }
        }

        return false;
    }

    public function salvarImagem(array $images, int $churchID)
    {
        try {

            $church = $this->getByID(churchID: $churchID, withAddress: false);

            $dataImages = ImageService::storeImages($images, 'churches', 'church_id', $church[0]->id);

            $this->churchModel->salvarImagem($dataImages, $church[0]->id);
        } catch (\Exception $e) {
            log_message('error', "Erro ao salvar image {$e->getMessage()}");
            die('Error saving data');
        }
    }

    public function deleteImage(int $churchID, string $image)
    {
        try {

            $church = $this->getByID(churchID: $churchID, withAddress: false);

            $this->churchModel->deleteImage($church->id, $image);

            ImageService::destroyImage('churches', $image);
        } catch (\Exception $e) {
            log_message('error', "Erro ao deletar image {$e->getMessage()}");
            die('Error deleting data');
        }
    }
}
