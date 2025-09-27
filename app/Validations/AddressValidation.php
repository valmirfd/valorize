<?php

namespace App\Validations;



class AddressValidation
{


    public function getRules(): array
    {
        return [
            'id' => [
                'rules' => 'permit_empty|is_natural_no_zero'
            ],
            'street' => [
                'rules' => "required",
            ],
            'number' => [
                'rules' => "permit_empty",
            ],
            'city' => [
                'rules' => "required"
            ],
            'district' => [
                'rules' => "required"
            ],
            'postalcode' => [
                'rules' => "required|exact_length[9]",
            ],
            'state' => [
                'rules' => "required|exact_length[2]"
            ],
        ];
    }
}
