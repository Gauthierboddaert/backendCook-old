<?php

namespace App\Repository;

interface RepositoryInterface
{
    public function findTenLastObject(int $recette = 10) : ?array;
}
