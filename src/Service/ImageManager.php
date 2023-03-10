<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Service\Helper\ConvertHelper;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager implements ImageManagerInterface
{
    private FileManagerInterface $fileManager;
    private string $image_directory;

    private const MAX_WIDTH = 350;
    private const MAX_HEIGHT = 500;

    private Imagine $imagine;
    public function __construct(FileManagerInterface $fileManager, string $image_directory)
    {
        $this->fileManager = $fileManager;
        $this->image_directory = $image_directory;
        $this->imagine = new Imagine();
    }

    public function downloadImage(FormInterface $form, Recipe $recipe, RecipeRepository $recetteRepository) : void
    {
        $image = $form->get('images') ->getData();
        foreach($image as $img){
            if(ConvertHelper::ConvertToMo($img->getSize()) > 1){
                $this->resize($img);
            }

            $this->fileManager->moveFileInDirectory($img, $recipe);
        }

        $recetteRepository->save($recipe, true);
    }

    public function resize(UploadedFile $filename): bool
    {
        $image = $this->fileManager->generateFileName(uniqid(),$filename->guessExtension());
        list($iwidth, $iheight) = getimagesize($filename);

        if($iheight or $iwidth === null)
        {
            return false;
        }

        $ratio = $iwidth / $iheight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($image);

        return true;
    }

}