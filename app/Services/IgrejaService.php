<?php

namespace App\Services;

use App\Models\IgrejaModel;
use CodeIgniter\Config\Factories;

class IgrejaService
{
    private $igrejaModel;
    private $user;

    public function __construct()
    {
        $this->igrejaModel = Factories::models(IgrejaModel::class);
        //$this->user = auth()->user();
    }

    public const SITUATION_NEW  = 'sede';
    public const SITUATION_USED = 'filial';


    //---------API------------------------//
    public function buscarIgrejasForUserAPI(bool $withAddress = true)
    {
        $igrejas = $this->igrejaModel->buscarIgrejasForUserAPI($withAddress);


        if (empty($igrejas)) {
            return [
                'igrejas' => []
            ];
        }

        $data = [];

        foreach ($igrejas as $igreja) {

            $data[] = [
                'id'         => $igreja->id,
                'user_id'    => $igreja->user_id,
                'nome'       => $igreja->nome,
                'telefone'   => $igreja->telefone,
                'cnpj'       => $igreja->cnpj,
                'code'       => $igreja->code,
                'situacao'   => $igreja->situacao,
                'address_id' => $igreja->address_id,
                'address'    => $igreja->address,
                'titular_id' => $igreja->titular_id,
                'is_sede'    => $igreja->is_sede,
                'ativo'      => $igreja->is_sede,
                'images'     => $igreja->image(),
                'created_at' => $igreja->created_at,
                'updated_at' => $igreja->updated_at,
                'superintendente_id' => $igreja->superintendente_id,
            ];
        }

        return $data;
    }
}
