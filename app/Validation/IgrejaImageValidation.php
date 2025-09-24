<?php

namespace App\Validation;



class IgrejaImageValidation
{


    public function getRules(?int $id = null): array
    {
        return [

            // product image
            'file_image' => [
                'label' => 'imagem da Igreja',
                'rules' => [
                    'uploaded[file_image]',
                    'mime_in[file_image,image/png,image/jpeg,image/jpg,image/webp]',
                    'max_size[file_image,2048]',
                    'max_dims[file_image,1920,1200]'
                ],
                'errors' => [
                    'uploaded' => 'O campo {field} é obrigatório',
                    'mime_in' => 'O campo {field} deve ser uma imagem PNG-JPEG-JPG-WEBP',
                    'max_size' => 'O campo {field} deve ter no máximo 200KB',
                    'max_dims' => 'O campo {field} deve ter dimenssão máxima de 1920X1200',
                ]
            ],

        ];
    }
}
