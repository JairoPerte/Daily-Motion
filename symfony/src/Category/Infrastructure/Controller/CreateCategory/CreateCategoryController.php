<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\Category\Application\UseCase\CreateCategory\CreateCategoryCommand;
use App\Category\Application\UseCase\CreateCategory\CreateCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateCategoryController extends AbstractController
{
    public function __construct(
        private CreateCategoryHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: '/v1/category', name: 'v1_category_create', methods: ['POST'])]
    public function index(
        #[MapRequestPayload] CreateCategoryRequest $request
    ): JsonResponse {
        $command = new CreateCategoryCommand(
            userId: $this->authContext->getUserId(),
            iconNumber: $request->iconNumber,
            name: $request->name
        );

        $category = ($this->handler)($command);

        $response = new CreateCategoryResponse(
            id: $category->getId()->getUuid(),
            iconNumber: $category->getIconNumber()->getInteger(),
            name: $category->getName()->getString()
        );

        return $this->json($response, 201);
    }
}
