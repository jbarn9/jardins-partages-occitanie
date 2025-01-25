<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    public function __construct(
        private ParameterBagInterface $params
    ) {}

    public function square(UploadedFile $picture, ?string $folder = '', ?int $width = 250): string
    {
        // give a new name to the image
        /** @var string $file 
         * @method md5 
         * @param uniqid (with entropy)
         */
        $file = md5(uniqid(rand(), true)) . '.webp';

        // get the image informations
        $pictureInfos = getimagesize($picture);

        if ($pictureInfos === false) {
            throw new Exception('Format d\'image incorrect');
        }

        // check the mime type of the image (image/png, image/jpeg, image/webp)
        switch ($pictureInfos['mime']) {
            case 'image/png':
                $sourcePicture = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $sourcePicture = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $sourcePicture = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception('Format d\'image incorrect');
        }
        // crop the image
        $imageWidth = $pictureInfos[0];
        $imageHeight = $pictureInfos[1];

        // check if the image is portrait or landscape
        switch ($imageWidth <=> $imageHeight) {
            case -1: // if the image is portrait
                $squareSize = $imageWidth;
                $srcX = 0;
                $srcY = ($imageHeight - $imageWidth) / 2;
                break;

            case 0: // if the image is square
                $squareSize = $imageWidth;
                $srcX = 0;
                $srcY = 0;
                break;

            case 1: // if the image is landscape
                $squareSize = $imageHeight;
                $srcX = ($imageWidth - $imageHeight) / 2;
                $srcY = 0;
                break;
        }

        // create a new empty image
        $resizedPicture = imagecreatetruecolor($width, $width);

        // generate the image content
        imagecopyresampled($resizedPicture, $sourcePicture, 0, 0, $srcX, $srcY, $width, $width, $squareSize, $squareSize);

        // create the storage path
        $path = $this->params->get('uploads_directory') . $folder;

        // create the folder if it doesn't exist
        if (!file_exists($path . '/mini/')) {
            mkdir($path . '/mini/', 0755, true);
        }

        // store the resized image
        imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $width . '-' . $file);


        // store the original image
        $picture->move($path . '/', $file);

        return $file;
    }
}
