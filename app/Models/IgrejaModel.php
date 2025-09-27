<?php

namespace App\Models;

use App\Entities\Address;
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


    /**
     * Retorna todas as Igrejas do usuário logado se $withAddress for true devolve também os endereços associados a elas
     *
     * @param boolean $withAddress
     * @return array
     */
    public function buscarIgrejasForUserAPI(bool $withAddress = true): array
    {
        $builder = $this;

        $tableFields = [
            'igrejas.*'
        ];

        $builder->select($tableFields);
        $builder->where('igrejas.superintendente_id', $this->user->id);
        $builder->groupBy('igrejas.nome'); // para não repetir registros
        $builder->orderBy('igrejas.id', 'DESC');

        $igrejas = $builder->findAll();

        if (!empty($igrejas)) {
            foreach ($igrejas as $igreja) {
                $igreja->images = $this->buscaImagemIgreja($igreja->id);
                if ($withAddress) {
                    $igreja->address = model(AddressModel::class)->asObject()->find($igreja->address_id);
                }
            }
        }

        return $igrejas;
    }

    /**
     * Retorna uma Igreja de acordo com o ID informado se $withAddress for true devolve também o endereço associado a ela
     *
     * @param string|null $igrejaID
     * @param boolean $withAddress
     * @return Igreja|null
     */
    public function getByID(
        string|null $igrejaID,
        bool $withAddress = true,

    ): Igreja|null {
        $igreja = $this->where(['id' => $igrejaID])->where('superintendente_id', $this->user->id)->first();

        if ($igreja === null) {
            return null;
        }

        if ($withAddress) {
            $igreja->address = model(AddressModel::class)->asObject()->find($igreja->address_id);
        }

        return $igreja;
    }

    /**
     * Método responsável em salvar ou editar as Igrejas
     *
     * @param Igreja $igreja
     * @param Address $address
     * @return boolean
     */
    public function store(Igreja $igreja, Address $address): bool
    {
        try {

            //Iniciamos a transaction
            $this->db->transException(true)->transStart();

            model(AddressModel::class)->save($address);
            $igreja->address_id = $address->id ?? model(AddressModel::class)->getInsertID();

            $this->save($igreja);

            //Finalizamos a transaction
            $this->db->transComplete();

            //Retorna o status da transaction (true or false)
            return $this->db->transStatus();
        } catch (\Throwable $th) {
            log_message('error', "Erro ao salvar a Igreja {$th->getMessage()}");
            return false;
        }
    }


    /**
     * Busca as imagens de acordo com o ID da Igreja
     *
     * @param integer $igrejaID
     * @return array
     */
    public function buscaImagemIgreja(int $igrejaID): array
    {
        return $this->db->table('igrejas_images')->where('igreja_id', $igrejaID)->get()->getResult();
    }
}
