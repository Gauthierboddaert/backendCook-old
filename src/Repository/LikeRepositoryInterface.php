<?php

namespace App\Repository;

use App\Entity\Recipe;

interface LikeRepositoryInterface
{
    public function UpdateLike(Recipe $recette, bool $isLiked);
}
