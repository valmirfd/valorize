<?php

namespace App\Validations;

class IgrejaImageValidation
{
    public function getRules(): array
    {
        return [
            'images' => [
                'rules' => [
                    'uploaded[images]',
                    'is_image[images]',
                    'mime_in[images,image/jpg,image/jpeg,image/png,image/webp]',
                    'max_size[images,2048]',
                    'max_dims[images,1920,1200]',
                ],
                'errors' => [
                    'mime_in' => 'Extenções aceitas: jpg-jpeg-png-webp',
                    'max_size' => 'Escolha imagens menores que 2048',
                    'max_dims' => 'Escolha imagens com dimensão menor que 1920x1200'
                ],
            ],

        ];
    }
}
