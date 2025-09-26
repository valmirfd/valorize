<?php

namespace App\Validation;



class IgrejaValidation
{


    public function getRules(?int $id = null): array
    {
        return [
            'id' => [
                'rules' => 'permit_empty|is_natural_no_zero'
            ],
            'nome' => [
                'rules' => "required|min_length[3]|max_length[128]",
                'errors' => [
                    'required' => 'Informe o nome da Igreja',
                    'min_length' => 'O nome deve ter no mínimo 3 caractéres',
                    'max_length' => 'O nome deve ter no máximo 128 caractéres',
                ],
            ],
            'telefone' => [
                'rules' => "required|exact_length[15]|is_unique[igrejas.telefone,id,{$id}]",
                'errors' => [
                    'required' => 'Informe o telefone da Igreja',
                    'exact_length' => 'O telefone deve ter exatamente 15 caractéres. Ex.: (00) 98888-8888',
                    'is_unique' => 'Este telefone já está cadastrado.',
                ],
            ],
            'cnpj' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'O campo CNPJ é obrigatório. Informe um CNPJ válido.',
                ],
            ],

        ];
    }
}
