<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Recipe;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager implements FileManagerInterface
{
    private string $image_directory;
    public function __construct(string $image_directory)
    {
        $this->image_directory = $image_directory;
    }

    public function moveFileInDirectory(UploadedFile $img, Recipe $recipe): void
    {
        $fichier = $this->generateFileName(uniqid(), $img->guessExtension());
        $img->move(
            $this->image_directory, $fichier
        );

        if($img->getClientOriginalExtension() == 'HEIC'){
            $this->convertImageByExtension($img, $fichier);
        }


        $fileImage = new Image();
        $fileImage->setName($fichier);
        $recipe->setImage($fileImage);
    }

    public function generateFileName(string $fileName, string $extension) : ?string
    {
         return $fileName.".".$extension;
    }


    public function convertImageByExtension(UploadedFile $img, string $fileName = '')
    {
        sleep(3);

        $imagine = new Imagine();
        $file = new \SplFileInfo($this->image_directory. '63d51f9ca878d.heic');
        $image = $imagine->open($file);
        dd($file, $image);
        $image->save($this->getParameter('images_directory') . '/' . $fileName . '.png');
        dd('here');

    }
}