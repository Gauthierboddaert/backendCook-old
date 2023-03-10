<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Service\RecipeManager;
use App\Service\UserManager;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private RecipeRepository $recipeRepository;
    private UserManager $userManager;
    private RecipeManager $recipeManager;

    public function __construct(RecipeRepository $recipeRepository, UserManager $userManager, RecipeManager $recipeManager)
    {
        $this->recipeRepository = $recipeRepository;
        $this->userManager = $userManager;
        $this->recipeManager = $recipeManager;
    }

    #[Route('/profil', name: 'app_user_index')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if(null == $this->getUser())
        {
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        }

        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
            'recettes' => $this->recipeManager->paginatorForTenRecipe($request, $this->recipeRepository->findRecipeByUser($this->getUser())),
            'age' => $this->userManager->getAge($this->getUser())
        ]);
    }

    #[Route('/profil/likes', name: 'app_user_like')]
    public function profilLikes(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if(null == $this->getUser())
        {
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        }

        return $this->render('user/profilLikes.html.twig', [
            'user' => $this->getUser(),
            'recettes' => $this->recipeManager->paginatorForTenRecipe($request, $this->recipeRepository->findLikesByUser($this->getUser())),
            'age' => $this->userManager->getAge($this->getUser())
        ]);
    }

    #[Route('/profil/favories', name: 'app_user_favories')]
    public function profilFavories(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if(null == $this->getUser())
        {
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        }

        return $this->render('user/profileFavories.html.twig', [
            'user' => $this->getUser(),
            'recettes' => $this->recipeManager->paginatorForTenRecipe($request, $this->recipeRepository->findLikesByUser($this->getUser())),
            'age' => $this->userManager->getAge($this->getUser())
        ]);
    }
}
