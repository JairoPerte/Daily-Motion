<?php

namespace App\Category\Infrastructure\Controller\GetCategory;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\Category\Application\UseCase\GetCategory\GetCategoryCommand;
use App\Category\Application\UseCase\GetCategory\GetCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCategoryController extends AbstractController
{
    public function __construct(
        private GetCategoryHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/category/{id}", name: "api_category_get", methods: ["GET"])]
    public function index(
        string $id
    ): JsonResponse {
        $command = new GetCategoryCommand(
            $id,
            $this->authContext->getUserId()
        );

        $categoria = ($this->handler)($command);

        $response = new GetCategoryResponse(
            id: $categoria->getId()->getUuid(),
            iconNumber: $categoria->getIconNumber()->getInteger(),
            name: $categoria->getName()->getString()
        );
        return $this->json($response, 200);
    }
}
