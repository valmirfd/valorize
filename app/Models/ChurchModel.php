<?php

namespace App\Models;

use App\Entities\Address;
use App\Entities\Church;
use App\Models\Basic\AppModel;


class ChurchModel extends AppModel
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = auth()->user();
    }

    protected $table            = 'churches';
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



    public function getChurchesForUserAPI(bool $withAddress = false): array
    {
        $builder = $this;

        $tableFields = [
            'churches.*'
        ];

        $builder->select($tableFields);
        $builder->where('churches.superintendente_id', $this->user->id);
        $builder->groupBy('churches.nome'); // para não repetir registros
        $builder->orderBy('churches.id', 'DESC');

        $churches = $builder->findAll();

        if (!empty($churches)) {
            foreach ($churches as $church) {
                $church->images = $this->getImageChurch($church->id);
                if ($withAddress) {
                    $church->address = model(AddressModel::class)->asObject()->find($church->address_id);
                }
            }
        }

        return $churches;
    }

    public function getByID(
        string|null $churchID,
        bool $withAddress = false

    ) {
        //$church = $this->where(['id' => $churchID])->where('superintendente_id', $this->user->id)->first();
        $church = $this->where(['id' => $churchID])->where(['superintendente_id' => $this->user->id])->first();

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

    public function getLastID(): int
    {
        return $this->getInsertID();
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
    private function getImageChurch(int $churchID): array
    {
        return $this->db->table('churches_images')->where('church_id', $churchID)->get()->getResult();
    }
}
