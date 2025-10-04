<?php

namespace App\Services;

use App\Entities\Address;
use App\Entities\Church;
use App\Models\ChurchModel;


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

        $churches = $this->churchModel->getChurchesForUserAPI(withAddress: $withAddress);

        $data = [];

        if (is_null($churches)) {
            return null;
        }

        foreach ($churches as $church) {

            $data[] = [
                "id" => $church->id,
                "nome" => $church->nome,
                "telefone" => $church->telefone,
                "cnpj" => $church->cnpj,
                "code" => $church->code,
                "situacao" => $church->situacao,
                "user_id" => $church->id,
                "address_id" => $church->user_id,
                "titular_id" => $church->titular_id,
                "is_sede" => $church->is_sede,
                "ativo" => $church->ativo,
                "superintendente_id" => $church->superintendente_id,
                "images" => $church->image(),
                "address" => $church->address->getFullAddress(),
                "created_at" => $church->created_at,
                "updated_at" => $church->updated_at,
                "deleted_at" => $church->deleted_at,
            ];
        }


        return $data;
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

        if ($church !== null) {
            $image = $church->image();
        } else {
            $image = [];
        }


        if ($church->address !== null) {
            $address = $church->address->getFullAddress();
        } else {
            $address = [];
        }

        $data = [];

        if (is_null($church)) {
            return null;
        }

        $data = [
            "id" => $church->id,
            "nome" => $church->nome,
            "telefone" => $church->telefone,
            "cnpj" => $church->cnpj,
            "code" => $church->code,
            "situacao" => $church->situacao,
            "user_id" => $church->id,
            "address_id" => $church->user_id,
            "titular_id" => $church->titular_id,
            "is_sede" => $church->is_sede,
            "ativo" => $church->ativo,
            "superintendente_id" => $church->superintendente_id,
            "images" => $image,
            "address" => $address,
            "created_at" => $church->created_at->date,
            "updated_at" => $church->updated_at->date,
            "deleted_at" => $church->deleted_at->date ?? null,
        ];

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
            $dataImages = ImageService::storeImages($images, 'churches', 'church_id', $churchID);
            $this->churchModel->salvarImagem(dataImages: $dataImages);
        } catch (\Exception $e) {
            log_message('error', "Erro ao salvar image {$e->getMessage()}");
            die('Erro ao salvar image Church');
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
