<?php

namespace App\Category\Infrastructure\Controller\UpdateCategory;

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
        private UpdateCategoryHandler $handler
    ) {}

    #[Route(path: "/api/category/{id}", name: "api_category_update", methods: ["PUT"])]
    public function index(
        #[MapRequestPayload] UpdateCategoryRequest $request,
        string $id
    ): JsonResponse {
        $command = new UpdateCategoryCommand(
            id: $id,
            name: $request->name,
            iconNumber: $request->iconNumber
        );

        $category = ($this->handler)($command);

        if ($category) {
            $response = new UpdateCategoryResponse(
                id: $category->getId()->getUuid(),
                userId: $category->getUserId()->getUuid(),
                iconNumber: $category->getName()->getName(),
                name: $category->getName()->getName()
            );
            return $this->json($response);
        } else {
            return $this->json(
                ["No existe una categoría con el id: $id"],
                404
            );
        }
    }
}
