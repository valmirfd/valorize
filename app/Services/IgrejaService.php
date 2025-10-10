<?php

namespace App\Services;

use App\Entities\Address;
use App\Entities\Igreja;
use App\Models\IgrejaModel;

class IgrejaService
{
    private IgrejaModel $igrejaModel;

    public function __construct()
    {
        $this->igrejaModel = model(IgrejaModel::class);
    }


    /**
     * Retorna um array com todas as Igrejas do usuário logado
     *
     * @return array
     */
    public function listarIgrejas(): array
    {

        $igrejas = $this->igrejaModel->listarIgrejas();

        $data = [];
        foreach ($igrejas as $igreja) {

            $data[] = [
                "id"         => $igreja->id,
                "nome"       => $igreja->nome,
                "telefone"   => $igreja->telefone,
                "cnpj"       => $igreja->cnpj,
                "code"       => $igreja->code,
                "situacao"   => $igreja->situacao,
                "user_id"    => $igreja->user_id,
                "address_id" => $igreja->address_id,
                "titular_id" => $igreja->titular_id,
                "is_sede"    => $igreja->is_sede,
                "ativo"      => $igreja->ativo,
                "superintendente_id" => $igreja->superintendente_id,
                "images"     => $igreja->image(),
                "created_at" => $igreja->created_at->humanize(),
                "updated_at" => $igreja->updated_at->humanize(),
            ];
        }


        return $data;
    }

    public function getByID(
        int $igrejaID,
        bool $withAddress = false,
        bool $withImages = false,
    ): Igreja|null {

        $igreja = $this->igrejaModel->getByID(igrejaID: $igrejaID, withAddress: $withAddress, withImages: $withImages);


        return $igreja;
    }

    public function showIgreja(
        int $igrejaID,
        bool $withAddress = false,
        bool $withImages = false
    ) {

        $data = [];

        $igreja = $this->igrejaModel->getByID(igrejaID: $igrejaID, withImages: $withImages, withAddress: $withAddress);


        if ($igreja !== null) {
            $image = $igreja->image();
            $address = $igreja->address->getFullAddress();

            $data = [
                "id" => $igreja->id,
                "nome" => $igreja->nome,
                "telefone" => $igreja->telefone,
                "cnpj" => $igreja->cnpj,
                "code" => $igreja->code,
                "situacao" => $igreja->situacao,
                "user_id" => $igreja->id,
                "address_id" => $igreja->user_id,
                "titular_id" => $igreja->titular_id,
                "is_sede" => $igreja->is_sede,
                "ativo" => $igreja->ativo,
                "superintendente_id" => $igreja->superintendente_id,
                "images" => $image,
                "address" => $address,
                "created_at" => $igreja->created_at->humanize(),
                "updated_at" => $igreja->updated_at->humanize(),
            ];
        } else {
            $image = [];
            $address = [];
        }






        return $data;
    }

    public function store(Igreja $igreja, Address $address): bool
    {
        return $this->igrejaModel->store(igreja: $igreja, address: $address);
    }

    public function salvarImagem(array $images, int $igrejaID)
    {
        try {
            $dataImages = ImageService::storeImages(
                images: $images,
                pathToStore: 'igrejas',
                propertyKey: 'igreja_id',
                propertyValue: $igrejaID
            );
            $this->igrejaModel->salvarImagem(dataImages: $dataImages);
        } catch (\Exception $e) {
            log_message('error', "Erro ao salvar image {$e->getMessage()}");
            die('Erro ao salvar image Igreja');
        }
    }

    public function getLastID(): int
    {
        return $this->igrejaModel->getLastID();
    }

    public function destroy(Igreja $igreja): bool
    {
        return $this->igrejaModel->destroy($igreja);
    }

    public function deleteImage(int $igrejaID, string $image): bool
    {

        return $this->igrejaModel->deleteImage(igrejaID: $igrejaID, image: $image);
    }
}
