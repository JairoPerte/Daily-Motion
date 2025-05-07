<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

use App\Category\Application\UseCase\CrateCategory\CreateCategoryCommand;
use App\Category\Application\UseCase\CrateCategory\CreateCategoryHandler;
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
        #[MapRequestPayload] CreateCategoryDTO $data
    ): JsonResponse {
        $command = new CreateCategoryCommand(
            userId: $data->userId,
            iconNumber: $data->iconNumber,
            name: $data->name
        );

        $category = ($this->handler)($command);

        return $this->json($category);
    }
}
