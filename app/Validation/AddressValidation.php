<?php

namespace App\Validation;



class AddressValidation
{


    public function getRules(): array
    {
        return [
            'street' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'Informe a rua.',

                ],
            ],
            'number' => [
                'rules' => "permit_empty",
            ],
            'city' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'Informe a cidade.',

                ],
            ],
            'district' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'Informe o bairro.',

                ],
            ],
            'postalcode' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'Informe o CEP.',

                ],
            ],
            'state' => [
                'rules' => "required|exact_length[2]",
                'errors' => [
                    'required' => 'Informe o Estado.',
                    'exact_length' => 'Informe apenas dois caractéres. Ex: MG',
                ],
            ],
        ];
    }
}
