<?php

namespace App\Service;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerInterface
{
    public function moveFileInDirectory(UploadedFile $img, Recipe $recipe) : void;
}