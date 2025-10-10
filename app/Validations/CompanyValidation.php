<?php

namespace App\Validations;

class CompanyValidation
{
    public function getRules(?int $id = null): array
    {
        return [
            'id' => [
                'label' => 'Id',
                'rules' => 'permit_empty|is_natural_no_zero'
            ],
            'name' => [
                'label' => 'Empresa',
                'rules' => "required|is_unique[companies.name,id,{$id}]",
                'errors' => [
                    'required' => 'Digite o nome da {field}',
                    'is_unique' => 'Esta {field} já existe',
                ],
            ],
            'email' => [
                'label' => 'Email',
                'rules' => "required|valid_email|is_unique[companies.email,id,{$id}]",
                'errors' => [
                    'required' => 'Digite o {field} da Empresa',
                    'is_unique' => 'Este {field} já existe',
                ],
            ],
            'phone' => [
                'label' => 'Telefone',
                'rules' => "required|is_unique[companies.phone,id,{$id}]",
                'errors' => [
                    'required' => 'Digite o {field} da Empresa',
                    'is_unique' => 'Este {field} já existe',
                ],
            ],
            'address' => [
                'label' => 'Endereço',
                'rules' => "required|max_length[170]",
                'errors' => [
                    'required' => 'Digite o {field} da Empresa',
                    'max_length' => 'O tamanho máximo do {field} é de 170 caractéres',
                ],
            ],

        ];
    }
}
