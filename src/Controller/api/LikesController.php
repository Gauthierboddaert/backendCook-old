<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Repository\LikeRepository;
use App\Repository\LikeRepositoryInterface;
use App\Repository\RecipeRepository;
use App\Repository\RecipeRepositoryInterface;
use App\Service\LikeManagerInterface;
use App\Service\LikeManagerManager;
use App\Service\RecipeManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class  LikesController extends AbstractController
{

    private LikeManagerInterface $likeInterface;

    public function __construct(
        LikeManagerInterface $likeInterface
    )
    {
        $this->likeInterface = $likeInterface;
    }

    #[Route('/api/likes/recipes/{id}',name: 'app_likes',methods: ['POST'])]
    public function index(int $id): JsonResponse
    {
        $user = $this->getUser();

        if($this->likeInterface->LikeRecipe($id, $user)){
            return new JsonResponse('like updated',Response::HTTP_OK);
        }

        return new JsonResponse('error');

    }
}
