<?php

namespace App\Category\Infrastructure\Controller\UpdateCategory;

use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Category\Application\UseCase\UpdateCategory\UpdateCategoryCommand;
use App\Category\Application\UseCase\UpdateCategory\UpdateCategoryHandler;
use App\Category\Infrastructure\Controller\UpdateCategory\UpdateCategoryResponse;

class UpdateCategoryController extends AbstractController
{
    public function __construct(
        private UpdateCategoryHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/category/{id}", name: "api_category_update", methods: ["PUT"])]
    public function index(
        #[MapRequestPayload] UpdateCategoryRequest $request,
        string $id
    ): JsonResponse {
        $command = new UpdateCategoryCommand(
            id: $id,
            name: $request->name,
            iconNumber: $request->iconNumber,
            userId: $this->authContext->getUserId()
        );

        $category = ($this->handler)($command);

        $response = new UpdateCategoryResponse(
            id: $category->getId()->getUuid(),
            iconNumber: $category->getName()->getString(),
            name: $category->getName()->getString()
        );
        return $this->json($response, 201);
    }
}
