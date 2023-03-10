<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Favoris;
use App\Entity\Like;
use App\Entity\Recipe;
use App\Entity\User;
use App\Repository\RecipeRepository;
use App\Repository\RepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RecipeManager implements RecipeManagerInterface
{

    private RouterInterface $router;
    private RecipeRepository $recipeRepository;
    private ImageManagerInterface $imageManager;
    private string $image_directory;
    private PaginatorInterface $paginator;
    private RepositoryInterface $repository;
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        RouterInterface $router,
        RecipeRepository $recipeRepository,
        ImageManagerInterface $imageManager,
        PaginatorInterface $paginator,
        RepositoryInterface $repository,
        EntityManagerInterface $entityManager,
        string $image_directory,

    )
    {
        $this->router = $router;
        $this->recipeRepository = $recipeRepository;
        $this->imageManager = $imageManager;
        $this->image_directory = $image_directory;
        $this->paginator = $paginator;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function setNewCategory(Category $category , Recipe $recette) : void
    {
        $recette->removeCategory($recette->getCategory()[0]);
        $recette->addCategory($category);
    }

    public function createNewRecipe(Recipe $recipe,User $user, FormInterface $form ) : bool
    {
        $like = new Like();
        $like->setUser($user);
        $like->setIsLike(false);
        $like->setRecipe($recipe);

        $favoris = new Favoris();
        $favoris->setIsFavoris(false);
        $favoris->addUser($user);
        $favoris->addRecette($recipe);

        //set the user here like this I don't need to show it in the form
        $recipe->setUsers($user);

        //allow to move image in directory of project uploads/image_recipe
        $this->imageManager->downloadImage($form, $recipe,$this->recipeRepository);

        $this->entityManager->persist($recipe);
        $this->entityManager->persist($like);
        $this->entityManager->persist($favoris);

        $this->entityManager->flush();

        return true;
    }


    public function paginatorForTenRecipe(Request $request,array $array) : SlidingPagination
    {
        return $this->paginator->paginate(
            $array,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );
    }


}