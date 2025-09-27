<?php

namespace App\Models;

use App\Entities\Igreja;
use App\Models\Basic\AppModel;


class IgrejaModel extends AppModel
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = auth()->user();
    }

    protected $table            = 'igrejas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Igreja::class;
    protected $useSoftDeletes   = false;
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
        'is_sede'
    ];


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';



    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeData', 'setCode', 'setUserId', 'setSuperId'];
    protected $beforeUpdate   = ['escapeData'];


    //---------API------------------------//
    public function buscarIgrejasForUserAPI(int|null $perPage = null, int|null $page = null)
    {
        $builder = $this;

        $tableFields = [
            'igrejas.*'
        ];

        $builder->select($tableFields);
        $builder->where('igrejas.user_id', $this->user->id);
        $builder->where('igrejas.user_id', $this->user->id);
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
