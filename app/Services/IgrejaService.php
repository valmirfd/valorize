<?php

namespace App\Services;

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

    public function showIgreja(
        string|null $igrejaID,
        bool $withAddress = false,
        bool $withImages = false
    ): array {

        $igreja = $this->igrejaModel->getByID(igrejaID: $igrejaID, withImages: $withImages, withAddress: $withAddress);


        if ($igreja !== null) {
            $image = $igreja->image();
            $address = $igreja->address->getFullAddress();
        } else {
            $image = [];
            $address = [];
        }

        $data = [];

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

        return $data;
    }
}
