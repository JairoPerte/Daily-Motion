<?php

namespace App\Category\Infrastructure\Controller\DeleteCategory;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\Category\Application\UseCase\DeleteCategory\DeleteCategoryCommand;
use App\Category\Application\UseCase\DeleteCategory\DeleteCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteCategoryController extends AbstractController
{
    public function __construct(
        private DeleteCategoryHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/category/{id}", name: "api_category_delete", methods: ["DELETE"])]
    public function index(
        string $id
    ): JsonResponse {
        $command = new DeleteCategoryCommand(
            $id,
            $this->authContext->getUserId()
        );
        ($this->handler)($command);
        return $this->json(["message" => "Category has been successfully deleted"], 204);
    }
}
