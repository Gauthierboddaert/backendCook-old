<?php

namespace App\Repository;

use App\Entity\Recipe;

interface RecipeRepositoryInterface
{
    public function findTopThreeBestLikedRecipe() : ?array;
}