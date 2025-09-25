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

    protected function setCode(array $data): array
    {
        do {
            $code = rand(10000000, 99999999);

            $result = $this->select('code')->where('code', $code)->countAllResults();
        } while ($result > 0);

        $data['data']['code'] = $code;

        return $data;
    }

    public function whereUser(): self
    {
        $this->where("{$this->table}.user_id", auth()->id());
        return $this;
    }
}
