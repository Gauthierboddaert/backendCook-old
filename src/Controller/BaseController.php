<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\SearchType;
use App\Repository\RecipeRepository;
use App\Service\ImageManagerInterface;
use App\Service\RecipeManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends AbstractController
{
    private RecipeRepository $recetteRepository;
    private ImageManagerInterface $imageInterface;

    public function __construct(
        RecipeRepository      $recetteRepository,
        ImageManagerInterface $imageInterface,
    )
    {
        $this->recetteRepository = $recetteRepository;
        $this->imageInterface = $imageInterface;
    }

    public function searchRecette(Request $request, string $redirect) : mixed
    {
        $recette = new Recipe();
        $form = $this->createForm(SearchType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           return  $this->redirectToRoute('app_homepage_search', [
               'name' => $form->getData()->getName()
           ]);
        }

        return $form;
    }
}