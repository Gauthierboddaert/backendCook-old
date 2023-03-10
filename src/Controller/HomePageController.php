<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Form\SearchType;
use App\Repository\RecipeRepository;
use App\Repository\RecipeRepositoryInterface;
use App\Repository\RepositoryInterface;
use App\Service\ImageManagerInterface;
use App\Service\RecipeManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomePageController extends BaseController
{
    private RecipeRepository $recipeRepository;
    private RepositoryInterface $repositoryInterface;
    private ImageManagerInterface $imageInterface;
    private RecipeManagerInterface $recipeManager;

    public function __construct(
        RecipeRepository      $recipeRepository,
        ImageManagerInterface $imageInterface,
        RepositoryInterface   $repositoryInterface,
        RecipeManagerInterface $recipeManager
    )
    {
        parent::__construct($recipeRepository, $imageInterface);
        $this->recipeRepository = $recipeRepository;
        $this->imageInterface = $imageInterface;
        $this->repositoryInterface = $repositoryInterface;
        $this->recipeManager = $recipeManager;
    }

    #[Route('/home', name: 'app_home_page_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        return $this->render('home_page/index.html.twig', [
            'bestThreeLikePublication' => $this->recipeRepository->findTopThreeBestLikedRecipe(),
            'recettes' => $this->recipeRepository->findThreeLastRecette(),
            'form' => $this->createForm(SearchType::class, null, ['action' => $this->generateUrl('app_homepage_search')])->createView()
        ]);
    }

    #[Route('/search', name: 'app_homepage_search', methods: ['GET', 'POST'])]
    public function search(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(SearchType::class, $recipe)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Recipe $recipe */
            $recipe = $form->getData();
        }

        return $this->render('Search/index.html.twig', [
            'recettes' => null === $recipe->getName() ? [] : $this->recipeRepository->filterByRecette($recipe->getName()),
            'name' => $recipe->getName(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/recette/new', name: 'app_home_page_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->recipeManager->createNewRecipe($recipe,$user, $form)){
                return $this->redirectToRoute('app_home_page_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('recette/new.html.twig', [
            'recette' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/recette/{id}', name: 'app_home_page_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recipe,
        ]);
    }

    #[Route('/recette/{id}/edit', name: 'app_home_page_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe): Response
    {
//        dd($recipe);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
//
        if ($form->isSubmitted() && $form->isValid()) {

            $this->recipeRepository->save($recipe, true);
            //move the image
            $this->imageInterface->downloadImage($form, $recipe,$this->recipeRepository,$this->image_directory);
            return $this->redirectToRoute('app_home_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/recette/{id}', name: 'app_home_page_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $this->recipeRepository->remove($recipe, true);
        }

        return $this->redirectToRoute('app_home_page_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/recette', name: 'app_homepage_recette', methods: ['GET'])]
    public function findAllRecette(PaginatorInterface $paginator, Request $request): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $this->recipeManager->paginatorForTenRecipe($request, $this->recipeRepository->findRecipeByOrder()),
            'likeByRecipe' => '',
            'form' => $this->createForm(SearchType::class, null, ['action' => $this->generateUrl('app_homepage_search')])->createView()
        ]);
    }
}
