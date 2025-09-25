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

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeData', 'setUserId', 'setCode'];
    protected $beforeUpdate   = ['escapeData'];



    public function getByCode(string $code): Igreja
    {
        return $this->where('code', $code)->first() ??
            throw new PageNotFoundException("Igreja {$code} não encontrada");
    }

    public function getByID(
        string|int|null $igrejaID,
        bool $withAddress = false,
    ): Igreja {
        $igreja = $this->where(['id' => $igrejaID])->first();

        if ($igreja === null) {
            throw new PageNotFoundException("Igreja não encontrada");
        }

        if ($withAddress) {
            $igreja->address = model(AddressModel::class)->find($igreja->address_id);
        }

        return $igreja;
    }

    //---------API------------------------//
    public function buscarIgrejasForUserAPI($superID, int|null $perPage = null, int|null $page = null)
    {

        $builder = $this;

        $tableFields = [
            'igrejas.*'
        ];

        $builder->select($tableFields);
        $builder->where('igrejas.superintendente_id', $superID);
        $builder->groupBy('igrejas.nome'); // para não repetir registros
        $builder->orderBy('igrejas.id', 'DESC');

        $igrejas = $this->paginate(perPage: $perPage, page: $page);

        if (!empty($igrejas)) {
            foreach ($igrejas as $igreja) {
                $igreja->images = $this->buscaImagemIgreja($igreja->id);
            }
        }

        return $igrejas;
    }

    public function buscaImagemIgreja(int $igrejaID): array
    {
        return $this->db->table('igrejas_images')->where('igreja_id', $igrejaID)->get()->getResult();
    }
}
