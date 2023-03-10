<?php

namespace App\Service;


use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface RecipeManagerInterface
{
    public function createNewRecipe(Recipe $recipe,User $user, FormInterface $form ) : bool;
}