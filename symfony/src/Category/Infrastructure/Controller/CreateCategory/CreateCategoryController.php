<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

use App\Category\Application\UseCase\CreateCategory\CreateCategoryCommand;
use App\Category\Application\UseCase\CreateCategory\CreateCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateCategoryController extends AbstractController
{
    public function __construct(
        private CreateCategoryHandler $handler
    ) {}

    #[Route(path: '/api/category/create', name: 'api_category_create', methods: ['POST'])]
    public function index(
        #[MapRequestPayload] CreateCategoryRequest $data
    ): JsonResponse {
        $command = new CreateCategoryCommand(
            userId: $data->userId,
            iconNumber: $data->iconNumber,
            name: $data->name
        );

        $category = ($this->handler)($command);

        $response = new CreateCategoryResponse(
            id: $category->getId()->getUuid(),
            userId: $category->getUserId()->getUuid(),
            iconNumber: $category->getIconNumber()->getIconNumber(),
            name: $category->getName()->getName()
        );

        return $this->json($response);
    }
}
