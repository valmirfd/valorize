<?php

namespace App\Models;

use App\Entities\Address;
use App\Entities\Igreja;
use App\Models\Basic\AppModel;
use App\Services\ImageService;

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
    ): Igreja|null {
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
                $igreja->endereco = model(AddressModel::class)->asArray()->find($igreja->address_id);
            }
        }

        // Retornamos a Igreja que pode ou não ter imagens
        return $igreja;
    }

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
            log_message('error', "Erro ao salvar Igreja {$th->getMessage()}");
            return false;
        }
    }

    public function salvarImagem(array $dataImages)
    {

        try {
            $this->db->transStart();
            $this->db->table('igrejas_images')->insertBatch($dataImages);
            $this->db->transComplete();
        } catch (\Exception $e) {
            log_message('error', "Erro ao salvar image {$e->getMessage()}");
            die('Error saving data');
        }
    }

    public function getLastID(): int
    {
        return $this->getInsertID();
    }

    public function destroy(Igreja $igreja): bool
    {

        $images = $igreja->images;

        try {

            //Iniciamos a transaction
            $this->db->transException(true)->transStart();

            //Exclui a Church
            $this->delete($igreja->id);

            //Exclui o endereço associado
            model(AddressModel::class)->delete($igreja->address_id);

            //Excluir no file system as imagens
            if ($images !== null || $images !== []) {

                foreach ($images as $image) {
                    $data = $image->image;

                    ImageService::destroyImage('igrejas', $data);
                }
            }

            //Finalizamos a transaction
            $this->db->transComplete();

            //Retorna o status da transaction (true or false)
            return $this->db->transStatus();
        } catch (\Throwable $th) {
            log_message('error', "Erro ao excluir Igreja {$th->getMessage()}");
            return false;
        }
    }

    public function deleteImage(int $igrejaID, string $image): bool
    {
        $criteria = [
            'igreja_id' => $igrejaID,
            'image'     => $image
        ];

        return $this->db->table('igrejas_images')->where($criteria)->delete();
    }

    //Métodos Privados
    private function getImageIgreja(int $igrejaID): array
    {
        return $this->db->table('igrejas_images')->where('igreja_id', $igrejaID)->get()->getResult();
    }
}
