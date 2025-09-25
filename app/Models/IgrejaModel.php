<?php

namespace App\Models;

use App\Entities\Igreja;
use App\Models\Basic\AppModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class IgrejaModel extends AppModel
{
    public function __construct()
    {
        parent::__construct();

        $this->beforeInsert = array_merge($this->beforeInsert, ['setUserId', 'setCode']);
    }

    protected $table            = 'igrejas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Igreja::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'address_id',
        'nome',
        'telefone',
        'cnpj',
        'code',
        'situacao',
        'superintendente_id',
        'titular_id',
        'is_sede',
        'ativo',
    ];

    public function getByCode(string $code): Igreja
    {
        return $this->where('code', $code)->first() ??
            throw new PageNotFoundException("Igreja {$code} não encontrada");
    }
}
