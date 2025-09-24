<?php

namespace App\Models;

use App\Entities\Address;
use App\Models\Basic\AppModel;


class AddressModel extends AppModel
{
    protected $table            = 'addresses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Address::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'street',
        'number',
        'city',
        'district',
        'postalcode',
        'state'
    ];
}
