<?php

namespace App\Validations;

class IgrejaValidation
{
    public function getRules(?int $id = null): array
    {
        return [
            'id' => [
                'rules' => 'permit_empty|is_natural_no_zero'
            ],
            'nome' => [
                'rules' => "required|min_length[3]|max_length[128]|is_unique[igrejas.nome,id,{$id}]",
                'errors' => [
                    'required' => 'Informe o nome da Igreja',
                    'min_length' => 'O nome deve ter no minimo 3 caractéres',
                    'max_length' => 'O nome deve ter no máximo 128 caractéres',
                    'is_unique' => 'Este nome já está cadastrado.',
                ],
            ],
            'telefone' => [
                'rules' => "required|validate_phone|exact_length[15]|is_unique[igrejas.telefone,id,{$id}]",
                'errors' => [
                    'required' => 'O campo Telefone é obrigatório.',
                    'exact_length' => 'O telefone deve ter exatamente 15 caractéres. Ex.: (00) 98888-8888',
                    'is_unique' => 'Este telefone já está cadastrado.',
                ],
            ],
            'cnpj' => [
                'rules' => "required|exact_length[18]|validate_cnpj|is_unique[igrejas.cnpj,id,{$id}]",
                'errors' => [
                    'required' => 'O campo CNPJ é obrigatório. Informe um CPF válido.',
                    'exact_length' => 'O CNPJ deve ter exatamente 18 caractéres. ex.: 00.000.000/0000-00',
                    'is_unique' => 'Este CNPJ não é válido ou já está cadastrado.',
                ],
            ],
            'situacao' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'O campo Situação é obrigatório'
                ],
            ],
            'is_sede' => [
                'rules' => "required",
                'errors' => [
                    'required' => 'O campo is_sede é obrigatório'
                ],
            ],

        ];
    }
}
