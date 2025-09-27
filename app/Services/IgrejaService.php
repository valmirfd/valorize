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
    public function buscarIgrejasForUserAPI(int|null $perPage = null, int|null $page = null)
    {
        $igrejas = $this->igrejaModel->buscarIgrejasForUserAPI($perPage, $page);
        $pager = (!empty($igrejas) ? $this->igrejaModel->pager->getDetails() : []);

        if (empty($igrejas)) {
            return [
                'igrejas' => [],
                'pager' => $pager
            ];
        }

        $data = [];

        foreach ($igrejas as $igreja) {

            $data[] = [
                'id'         => $igreja->id,
                'belongs_to' => $igreja->username,
                'images'     => $igreja->image(),
                'nome'       => $igreja->nome,
                'telefone'   => $igreja->telefone,
                'codigo'     => $igreja->codigo,
                'address'    => $igreja->address(),
                'created_at' => $igreja->created_at,
                'updated_at' => $igreja->updated_at,
            ];
        }

        return [
            'igrejas' => $data,
            'pager' => $pager
        ];
    }
}
