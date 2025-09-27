<?php

namespace App\Services;

use Error;

class ImageService
{
    public static function storeImages(
        array|object $images,
        string $pathToStore,
        string|int $propertyKey = 'propertyKey',
        string|int $propertyValue = ''
    ): array {

        // É apenas uma imagem (objeto de uma imagem 'upada')?
        if (is_object($images)) {

            self::worksWithImage($images, $pathToStore);
        }

        // Temos uma array de imagens
        $uploadedImages = [];

        foreach ($images['images'] as $image) {

            $uploadedImages[] = [
                $propertyKey => $propertyValue,
                'image'      => self::worksWithImage($image, $pathToStore)
            ];
        }

        return $uploadedImages;
    }


    public static function showImage(string $imagePath, string $image, string $sizeImage = 'regular')
    {
        if ($sizeImage == 'small') {
            $imagePath = ROOTPATH . "public/assets/images/$imagePath/small/$image";
        } else {
            $imagePath = ROOTPATH . "public/assets/images/$imagePath/$image";
        }

        $fileInfo = new \finfo(FILEINFO_MIME);

        $fileType = $fileInfo->file($imagePath);

        header("Content-Type: $fileType");

        header("Content-Length: " . filesize($imagePath));

        readfile($imagePath);

        exit;
    }


    public static function destroyImage(string $pathToImage, string $imageToDestroy)
    {
        $regularImageToDestroy  = ROOTPATH . "public/assets/images/{$pathToImage}/{$imageToDestroy}";

        $smallImageToDestroy    = ROOTPATH . "public/assets/images/{$pathToImage}/small/{$imageToDestroy}";

        if (is_file($regularImageToDestroy)) {

            unlink($regularImageToDestroy);
        }

        if (is_file($smallImageToDestroy)) {

            unlink($smallImageToDestroy);
        }
    }


    private static function worksWithImage(object $image, string $pathToStore): string
    {

        $image->move(ROOTPATH . "public/assets/images/$pathToStore", $image->getRandomName());

        $imagePath = "{$pathToStore}/{$image->getName()}";

        $imagePath = ROOTPATH . "public/assets/images/{$imagePath}";

        $imageSmallPath =  ROOTPATH . "public/assets/images/$pathToStore/small/";


        // Existe o diretório contido na variável $imageSmallPath?
        if (!is_dir($imageSmallPath)) {
            // Não existe. Então podemos criá-lo
            mkdir($imageSmallPath);
        }

        // Manulamos a imagem para criamos uma cópia um pouco menor que a original
        service('image')
            ->withFile($imagePath) // arquivo original
            ->resize(275, 275, true, 'center')
            ->save($imageSmallPath . $image->getName());

        return $image->getName();
    }
}
