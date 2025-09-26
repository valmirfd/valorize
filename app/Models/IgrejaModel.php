<?php

namespace App\Models;

use App\Entities\Address;
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
    protected $beforeInsert   = ['escapeData', 'setUserId', 'setCode', 'setSuperId'];
    protected $beforeUpdate   = ['escapeData'];


    /**
     * Metodo responsável em buscar todas as Igrejas no banco de dados de acordo com o ID do usuário logado
     *
     * @param [type] $userID
     * @return array
     */
    public function buscarIgrejasForUserAPI($userID): array
    {

        $builder = $this;

        $tableFields = [
            'igrejas.*'
        ];

        $builder->select($tableFields);
        $builder->where('igrejas.superintendente_id', $userID);
        $builder->groupBy('igrejas.nome'); // para não repetir registros
        $builder->orderBy('igrejas.id', 'DESC');

        $igrejas = $builder->findAll();

        if (!empty($igrejas)) {
            foreach ($igrejas as $igreja) {
                $igreja->images = $this->buscaImagemIgreja($igreja->id);
                $igreja->address = model(AddressModel::class)->asObject()->find($igreja->address_id);
            }
        }

        return $igrejas;
    }

    /**
     * Retorna uma Igreja pelo ID
     *
     * @param string|integer|null $igrejaID
     * @param boolean $withAddress
     * @return Igreja|array|null
     */
    public function getByID(
        string|int|null $igrejaID,
        bool $withAddress = false,
    ): Igreja|array|null {
        $data = [];

        $igreja = $this->where(['id' => $igrejaID])->where('superintendente_id', auth()->user()->superintendente_id)->asObject()->first();

        if ($igreja === null) {
            return null;
        }

        $igreja->images = $this->buscaImagemIgreja($igreja->id);

        if ($withAddress) {
            $igreja->address = model(AddressModel::class)->asObject()->find($igreja->address_id);
        }

        $data[] = $igreja;

        return $data;
    }


    /**
     * Método responsável tanto para salvar como para editar uma Igreja
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
     * Bucar as imagens da Igreja de acordo com o ID informado
     *
     * @param integer $igrejaID
     * @return array
     */
    public function buscaImagemIgreja(int $igrejaID): array
    {
        return $this->db->table('igrejas_images')->where('igreja_id', $igrejaID)->get()->getResult();
    }

    /**
     * Busca uma Igreja de acordo com o código informado
     *
     * @param string $code
     * @return Igreja
     */
    public function getByCode(string $code): Igreja
    {
        return $this->where('code', $code)->first() ??
            throw new PageNotFoundException("Igreja {$code} não encontrada");
    }
}
