<?php

namespace App\Service;

use App\Entity\Like;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\LikeRepository;
use App\Repository\LikeRepositoryInterface;
use App\Repository\RecipeRepositoryInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\Tools\DebugUnitOfWorkListener;

class LikeManager implements LikeManagerInterface
{

    private RecipeRepositoryInterface $recipeRepositoryInterface;
    private LikeRepositoryInterface $likeRepositoryInterface;
    private LikeRepository $likeRepository;
    private UserRepository $userRepository;

    public function __construct(
        LikeRepository            $likeRepository,
        RecipeRepositoryInterface $recipeRepositoryInterface,
        LikeRepositoryInterface   $likeRepositoryInterface,
        UserRepository $userRepository
    )
    {
        $this->recipeRepositoryInterface = $recipeRepositoryInterface;
        $this->likeRepositoryInterface = $likeRepositoryInterface;
        $this->likeRepository = $likeRepository;
        $this->userRepository = $userRepository;
    }
    public function LikeRecipe(int $id, User $user) : bool
    {
        $recipe = $this->recipeRepositoryInterface->findOneBy([
            'id' => $id,
            'users' => 1
            //'users' => $user
        ]);
        if(null !== $recipe)
        {
            $this->createLikeInRecipeForUser($recipe, 1);
            $like = $recipe->getLikes()->first();
            $like->isIsLike() ? $like->setIsLike(false) : $like->setIsLike(true);
            $this->likeRepositoryInterface->save($like, true);
            return true;
        }

        return false;

    }

    private function createLikeInRecipeForUser(Recipe $recipe, int $user) : void
    {
       $likeExist =  $this->likeRepositoryInterface->findOneBy([
            'recipe' => $recipe->getId(),
            'user' => $user
        ]);

       $userTest = $this->userRepository->find($user);

        if(null === $likeExist)
        {
            $like = new Like();
            $like->setRecipe($recipe);
            $like->setIsLike(false);
            $like->setUser($userTest);

            $this->likeRepositoryInterface->save($like, true);
        }
    }
}