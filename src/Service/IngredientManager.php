<?php

namespace App\Service;

use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;

class IngredientManager
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addIngredientsFromXlsx(array $data) : bool
    {
        foreach ($data as $row){
            if(null !== $row[0])
            {
                try{
                    $ingredient = new Ingredient();
                    $ingredient->setName($row[0]);
                    (!is_string($row[1])) ? $ingredient->setProteines($row[1]) : $ingredient->setProteines(intval(($row[1])));
                    (!is_string($row[2])) ? $ingredient->setGlucides($row[2]) : $ingredient->setGlucides(intval($row[2]));
                    (!is_string($row[3])) ? $ingredient->setLipides($row[3]) : $ingredient->setLipides(intval($row[3]));
                    $this->entityManager->persist($ingredient);
                }catch (\Exception $e){
                    return false;
                }

            }
        }

        $this->entityManager->flush();

        return true;
    }
}