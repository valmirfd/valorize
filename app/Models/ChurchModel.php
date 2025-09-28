<?php

namespace App\Models;

use App\Entities\Address;
use App\Entities\Church;
use App\Models\Basic\AppModel;
use App\Validations\IgrejaValidation;

class ChurchModel extends AppModel
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
    protected $returnType       = Church::class;
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



    public function buscarIgrejasForUserAPI(bool $withAddress = false): array
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

    public function getByID(
        string|null $churchID,
        bool $withAddress = true,

    ) {
        $church = $this->where(['id' => $churchID])->where('superintendente_id', $this->user->id)->first();

        if ($church === null) {
            return null;
        }

        if ($withAddress) {
            $church->address = model(AddressModel::class)->find($church->address_id);
        }

        return $church;
    }

    public function store(Church $church, Address $address): bool
    {
        try {

            //Iniciamos a transaction
            $this->db->transException(true)->transStart();

            model(AddressModel::class)->save($address);
            $church->address_id = $address->id ?? model(AddressModel::class)->getInsertID();

            $this->save($church);

            //Finalizamos a transaction
            $this->db->transComplete();

            //Retorna o status da transaction (true or false)
            return $this->db->transStatus();
        } catch (\Throwable $th) {
            log_message('error', "Erro ao salvar Church {$th->getMessage()}");
            return false;
        }
    }

    public function destroy(Church $church): bool
    {
        try {

            //Iniciamos a transaction
            $this->db->transException(true)->transStart();

            $this->delete($church->id);

            model(AddressModel::class)->delete($church->address_id);

            //Finalizamos a transaction
            $this->db->transComplete();

            //Retorna o status da transaction (true or false)
            return $this->db->transStatus();
        } catch (\Throwable $th) {
            log_message('error', "Erro ao excluir Church {$th->getMessage()}");
            return false;
        }
    }





    //Métodos Privados
    private function buscaImagemIgreja(int $igrejaID): array
    {
        return $this->db->table('igrejas_images')->where('igreja_id', $igrejaID)->get()->getResult();
    }
}
