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
        bool $withAddress = false,
        bool $withImages = false

    ) {
        //$church = $this->where(['id' => $churchID])->where('superintendente_id', $this->user->id)->first();
        $church = $this->where(['id' => $churchID])->where(['superintendente_id' => $this->user->id])->first();

        if ($church === null) {
            return null;
        }

        if ($withAddress) {
            $church->address = model(AddressModel::class)->find($church->address_id);
        }

        if ($withImages) {
            $church->images = $this->getImageChurch(churchID: $church->id);
        }

        return $church;
    }

    public function buscaIgreja(
        string|null $churchID,
        bool $withAddress = false,
        bool $withImages = false,
        bool $withDeleted = false
    ) {

        $builder = $this;

        $tableFields = [
            'churches.*',

        ];

        $builder->select($tableFields);
        $builder->withDeleted($withDeleted);
        $builder->where('churches.id', $churchID);
        $builder->where('churches.superintendente_id', $this->user->id);
        $church = $builder->find($churchID);

        // Foi encontrado uma church?
        if (!is_null($church)) {

            if ($withImages) {
                $church->images = $this->getImageChurch($church->id);
            }

            if ($withAddress) {
                $church->address = model(AddressModel::class)->find($church->address_id);
            }
        }

        // Retornamos o anúncio que pode ou não ter imagens
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

            // recupero apenas os adverts_id da tabela de imagens
            //$advertsIDS = array_column($this->db->table('adverts_images')->select('advert_id')->get()->getResultArray(), 'advert_id');
            //$builder->whereIn('adverts.id', $advertsIDS); // apenas em anúncios que possuem imagem

            /*$imagesIDS = array_column($this->db->table('churches_images')->select('church_id', 'image')->get()->getResultArray(), 'church_id');
            */





            //Iniciamos a transaction
            $this->db->transException(true)->transStart();

            $this->delete($church->id);

            model(AddressModel::class)->delete($church->address_id);

            /*foreach ($imagesIDS as $imageID) {
                $this->db->table('churches_images')->where($imageID)->delete();
            }*/

            //Finalizamos a transaction
            $this->db->transComplete();

            //Retorna o status da transaction (true or false)
            return $this->db->transStatus();
        } catch (\Throwable $th) {
            log_message('error', "Erro ao excluir Church {$th->getMessage()}");
            return false;
        }
    }

    public function salvarImagem(array $dataImages)
    {

        try {
            $this->db->transStart();
            $this->db->table('churches_images')->insertBatch($dataImages);
            $this->db->transComplete();
        } catch (\Exception $e) {
            log_message('error', "Erro ao salvar image {$e->getMessage()}");
            die('Error saving data');
        }
    }

    /**
     * Método responsável em excluir uma imagem no banco de dados de acordo o o ID da Church e o nome da Image
     *
     * @param integer $churchID
     * @param string $image
     * @return boolean
     */
    public function deleteImage(int $churchID, string $image): bool
    {
        $criteria = [
            'church_id' => $churchID,
            'image'     => $image
        ];

        return $this->db->table('churches_images')->where($criteria)->delete();
    }





    //Métodos Privados
    private function getImageChurch(int $churchID): array
    {
        return $this->db->table('churches_images')->where('church_id', $churchID)->get()->getResult();
    }
}
