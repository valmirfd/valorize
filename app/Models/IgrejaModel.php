<?php

namespace App\Models;

use App\Entities\Igreja;
use App\Models\Basic\AppModel;


class IgrejaModel extends AppModel
{
    protected $table            = 'igrejas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Igreja::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nome',
        'telefone',
        'cnpj',
        'codigo',
        'situacao',
        'cep',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'superintendente_id',
        'titular_id',
        'is_sede',
        'descricao',
        'ativo',
    ];

    // Callbacks
    protected $beforeInsert   = ['setCode'];
}
