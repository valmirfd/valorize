<?php

namespace App\Validations;



class AddressValidation
{


    public function getRules(): array
    {
        return [
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
