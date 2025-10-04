<?php

namespace App\Entities;


use CodeIgniter\Entity\Entity;

class Church extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'ativo'    => 'boolean',
        'is_sede'  => 'boolean',
    ];

    public function unsetAuxiliaryAttributes()
    {
        //unset($this->attributes['address']);
        unset($this->attributes['images']);
    }

    public function exibeSituacao(): string
    {
        return $this->attributes['situacao'] === 'sede' ? '<span class="badge badge-success text-white">Sede</span>' : '<span class="badge badge-success text-white">Filial</span>';
    }

    /**
     * Método que retorna a imagem ou caminho conforme se fizer necessário
     *
     * @param string $classImage
     * @param string $sizeImage
     * @return mixed
     */
    public function image(string $classImage = '', string $sizeImage = 'regular')
    {
        if (empty($this->attributes['images'])) {

            return $this->handleWithEmptyImage($classImage);
        }

        if (is_string($this->attributes['images'])) {

            return $this->handleWithSingleImage($classImage, $sizeImage);
        }

        if (url_is('api/churches*')) {

            return $this->handleWithImagesForAPI();
        }
    }


    /// Métodos privados

    private function handleWithEmptyImage(string $classImage): string
    {

        if (url_is('api/churches*')) {

            return site_url('assets/images/no_image.png');
        }

        return img(
            [
                'src'       => site_url('assets/images/no_image.png'),
                'alt'       => 'No image yet',
                'name'     => 'No image yet',
                'class'     => $classImage,
                'width'     => '50',
            ]
        );
    }


    private function handleWithSingleImage(string $classImage, string $sizeImage): string
    {

        if (url_is('api/churches*')) {

            return $this->buildRouteForImageAPI($this->attributes['images']);
        }

        return img(
            [
                //'src'       => route_to('imagem.igreja', $this->attributes['images'], $sizeImage),
                'src'       => route_to('image.church', $this->attributes['images'], $sizeImage),
                'alt'       => $this->attributes['nome'],
                'name'     => $this->attributes['nome'],
                'class'     => $classImage,
                'width'     => '50',
            ]
        );
    }


    private function handleWithImagesForAPI(): array
    {
        $images = [];

        foreach ($this->attributes['images'] as $image) {

            $images[] = $this->buildRouteForImageAPI($image->image);
        }

        return $images;
    }


    private function buildRouteForImageAPI(string $image): string
    {
        //$url = ImageService::showImage(imagePath: 'churches', image: $image, sizeImage: $sizeImage);

        return site_url("assets/images/churches/small/$image");
    }
}
