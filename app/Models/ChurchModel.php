<?php

namespace App\Models;

use CodeIgniter\Model;

class ChurchModel extends Model
{
    protected $table            = 'churches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'phone',
        'email',
        'address',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = false;


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
