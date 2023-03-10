<?php

namespace App\Controller\api;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Service\RecipeManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api')]
class RecetteController extends AbstractController
{
    private RecipeRepository $recetteRepository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private UserRepository $userRepository;
    private CategoryRepository $categoryRepository;
    private RecipeManager $recetteManager;

    public function __construct(
        CategoryRepository     $categoryRepository,
        UserRepository         $userRepository,
        RecipeRepository       $recetteRepository,
        SerializerInterface    $serializer,
        EntityManagerInterface $entityManager,
        RecipeManager $recetteManager
    )
    {
        $this->recetteRepository = $recetteRepository;
        $this->categoryRepository = $categoryRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->recetteManager = $recetteManager;
    }

    #[Route('/recette', name: 'app_recette', methods: 'GET')]
    public function index(): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize($this->recetteRepository->findAll(),
            'json', ["groups" => "getRecette"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/recette/{id}', name: 'app_one_recette', methods: 'GET')]
    public function findOneRecette(int $id): JsonResponse
    {
        $recette = $this->recetteRepository->findOneBy(['id' => $id]);

        if(null === $recette)
            return new JsonResponse($this->serializer->serialize("Recipe doesn't exist",'json'),Response::HTTP_BAD_REQUEST, [], true);

        return new JsonResponse(
            $this->serializer->serialize($recette,
                'json', ["groups" => "getRecette"]),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/recette/{id}', name: 'app_delete_recette', methods: 'DELETE')]
    public function deleteRecette(int $id): JsonResponse
    {
        $recette = $this->recetteRepository->findOneBy(['id' => $id]);
//        dd($recette);
        if(null === $recette)
            return new JsonResponse($this->serializer->serialize("Recipe doesn't exist",'json'),Response::HTTP_BAD_REQUEST, [], true);

        $this->recetteRepository->remove($recette);
        $this->entityManager->flush();
        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );

    }

    #[Route('/recette', name: 'app_post_recette', methods: 'POST')]
    public function postRecette(Request $request): JsonResponse
    {
         $request = $request->toArray();

         $recette = new Recipe();
         $recette->setName($request['name']);
         $recette->setDescriptions($request['description']);
         $recette->setUsers($this->userRepository->findOneBy(['email' => $request['email']]));
         $recette->addCategory($this->categoryRepository->findOneBy(['name' => $request['category']]));
         $this->recetteRepository->save($recette, true);

         return new JsonResponse($this->serializer->serialize(
             'You have add a new Recipe :) !'
             ,'json',[]),
             Response::HTTP_CREATED,
             [],
             true);
    }

    #[Route('/recette/{id}', name: 'app_update_recette', methods: 'PUT')]
    public function updateRecette(int $id, Request $request) : JsonResponse
    {
        $request = $request->toArray();
        $recette = $this->recetteRepository->findOneBy(['id' => $id]);
        $category = $this->categoryRepository->findOneBy(['name' => $request['category']]);

        ( !empty($request['name'])) ? $recette->setName($request['name']) : '' ;
        (!empty($request['description'])) ? $recette->setDescriptions($request['description']) : '';
        (!empty($request['category'])) ? $this->recetteManager->setNewCategory($category, $recette) : '';

        $this->recetteRepository->save($recette, true);

        return new JsonResponse();
    }

}
