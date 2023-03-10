<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Imagine\Image\ImageInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageManagerInterface
{
    public function downloadImage(FormInterface $form, Recipe $recette, RecipeRepository $recetteRepository) : void;
    public function resize(UploadedFile $img) : bool;
}