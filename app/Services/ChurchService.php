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

        if (empty($churches)) {
            return null;
        }

        $data = [];

        foreach ($churches as $church) {

            $data[] = [
                'id' => $church->id,
                'nome' => $church->nome,
                'telefone' => $church->telefone,
                'cnpj' => $church->cnpj,
                'code' => $church->code,
                'situacao' => $church->situacao,
                'ativo' => $church->ativo,
                'user_id' => $church->user_id,
                'is_sede' => $church->is_sede,
                'address_id' => $church->address_id,
                'titular_id' => $church->titular_id,
                'superintendente_id' => $church->superintendente_id,
                'images' => $church->image(),
                'created_at' => $church->created_at,
                'updated_at' => $church->updated_at,
                'address' => $church->address,
            ];
        }

        return $data;
    }

    /**
     * Retorna uma única Church de acordo com o ID informado
     *
     * @param string|null $churchID
     * @param boolean $withAddress
     * @return array|object|null
     */
    public function getByID(
        string|null $churchID,
        bool $withAddress = true,

    ): array|object|null {
        $church = $this->churchModel->getByID(churchID: $churchID, withAddress: $withAddress);

        if (empty($church)) {
            return null;
        }

        return $church;
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
        return $this->churchModel->destroy(church: $church);
    }
}
