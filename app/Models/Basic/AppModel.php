<?php

namespace App\Models\Basic;


use CodeIgniter\Model;

abstract class AppModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setSQLMode();
    }

    protected function setSQLMode(): void
    {
        $this->db->simpleQuery("set session sql_mode=''");
    }


    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = false;


    protected function escapeData(array $data): array
    {
        return esc($data);
    }

    protected function setUserId(array $data): array
    {
        $data['data']['user_id'] = auth()->id();
        return $data;
    }

    protected function setSuperId(array $data): array
    {
        $data['data']['superintendente_id'] = auth()->id();
        return $data;
    }

    public function whereUser(): self
    {
        $this->where("{$this->table}.user_id", auth()->id());
        return $this;
    }
}
