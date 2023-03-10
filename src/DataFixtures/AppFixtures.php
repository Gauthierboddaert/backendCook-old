<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Ingredient;
use App\Entity\Like;
use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $admin = new User();
        $this->CreateUsers($manager, $user, $admin);

        for ($i = 0; $i < 20; $i++)
        {
            $recipe = new Recipe();
            $recipeStep = new RecipeStep();
            $recipeStep2 = new RecipeStep();

            $like = new Like();
            $ingredient = new Ingredient();
            $category = new Category();
            $image = new Image();


            $category->setName('VEGETARIEN');
            $like->setIsLike(false);
            $manager->persist($category);

            $image->setName('ham.jpeg');
            $manager->persist($image);

            $recipe->setDescriptions('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum cupiditate enim eveniet illum magni quidem, quis recusandae reprehenderit velit! Debitis molestias numquam sit vitae. At distinctio natus recusandae rem voluptatem.'.$i);
            $recipe->setName('Name here'.$i);
            $recipe->setCreationTime($i);
            $recipe->setCreatedAt(new \DateTime());
            $recipe->setUpdatedAt(new \DateTime());
            $recipe->setNumberOfPersons(1);
            $recipe->setUsers($user);
            $recipe->setImage($image);
            $recipe->addCategory($category);
            $recipeStep->setName('firstStep'.$i);
            $recipeStep2->setName('secondStep'.$i);
            $recipeStep->setDescriptions('descritpion1'.$i);
            $recipeStep2->setDescriptions('descritpion2'.$i);
            $recipeStep->setRecipe($recipe);
            $recipeStep2->setRecipe($recipe);
            $recipe->addRecipeStep($recipeStep);
            $recipe->addRecipeStep($recipeStep2);
            $like->setRecipe($recipe);
            $like->setUser($user);
            $recipe->addLike($like);
            $ingredient->setName('tomate'.$i);
            $recipe->addIngredient($ingredient);
            $ingredient->addRecette($recipe);

            $manager->persist($like);
            $manager->persist($recipeStep);
            $manager->persist($recipeStep2);
            $manager->persist($ingredient);
            $manager->persist($recipe);
        }
        $manager->flush();
    }

    private function CreateUsers(ObjectManager $manager,User $user, User $admin) : void
    {
        $user->setEmail('boddaert.gauthier@gmail.com');
        $user->setName('Gauthier');
        $user->setLastname('Boddaert');
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "coucou"));
        $user->setRoles(['ROLE_ADMIN']);
        $image = new Image();
        $image->setName('moi.JPG');
        $user->setImage($image);
        $user->setDateOfBirth(new \DateTime());

        $admin->setEmail('gboddaert@insitaction.com');
        $admin->setName('Gauthier');
        $admin->setLastname('Boddaert');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "coucou"));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setDateOfBirth(new \DateTime());

        $manager->persist($user);
        $manager->persist($admin);

    }
}
