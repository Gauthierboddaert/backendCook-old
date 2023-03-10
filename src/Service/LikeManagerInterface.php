<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use Symfony\Component\Form\FormInterface;

interface LikeManagerInterface
{
    public function LikeRecipe(int $id, User $user ) : bool;
}