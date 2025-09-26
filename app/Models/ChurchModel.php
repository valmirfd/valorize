<?php

namespace App\Models;

use App\Models\Basic\AppModel;


class ChurchModel extends AppModel
{
    protected $table            = 'churches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
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
    protected $beforeInsert   = ['escapeData', 'setUserId', 'setSuperId'];
    protected $beforeUpdate   = ['escapeData'];
}
