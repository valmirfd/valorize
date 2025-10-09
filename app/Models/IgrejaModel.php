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


    /**
     * Retorna um array com todas as Igrejas do usuário logado
     *
     * @return array
     */
    public function listarIgrejas(): array
    {
        $builder = $this;

        $tableFields = [
            'igrejas.*',
        ];

        $builder->select($tableFields);
        $builder->where('igrejas.superintendente_id', $this->user->id);
        $builder->groupBy('igrejas.nome'); // para não repetir registros
        $builder->orderBy('igrejas.id', 'DESC');

        $igrejas = $builder->findAll();

        if (!empty($igrejas)) {
            foreach ($igrejas as $igreja) {
                $igreja->images = $this->getImageIgreja($igreja->id);
            }
        }

        return $igrejas;
    }

    public function getByID(
        int $igrejaID,
        bool $withAddress = false,
        bool $withImages = false,
    ): Igreja {
        $builder = $this;

        $tableFields = [
            'igrejas.*'
        ];

        $builder->select($tableFields);
        $builder->where('igrejas.superintendente_id', $this->user->id);

        $igreja = $builder->find($igrejaID);

        // Foi encontrado uma Igreja?
        if (!is_null($igreja)) {

            if ($withImages) {
                // Sim... então podemos buscar as imagens da mesma
                $igreja->images = $this->getImageIgreja($igreja->id);
            }

            if ($withAddress) {
                $igreja->address = model(AddressModel::class)->find($igreja->address_id);
            }
        }

        // Retornamos a Igreja que pode ou não ter imagens
        return $igreja;
    }

    //Métodos Privados
    private function getImageIgreja(int $igrejaID): array
    {
        return $this->db->table('igrejas_images')->where('igreja_id', $igrejaID)->get()->getResult();
    }
}
