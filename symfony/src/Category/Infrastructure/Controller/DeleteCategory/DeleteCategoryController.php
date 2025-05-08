<?php

namespace App\Category\Infrastructure\Controller\DeleteCategory;

use App\Category\Application\UseCase\CreateCategory\CreateCategoryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteCategoryController extends AbstractController
{
    public function __construct(
        private CreateCategoryHandler $handler
    ) {}

    #[Route(path: "/api/category/{id}", name: "api_category_delete", methods: ["DELETE"])]
    public function index(
        string $id
    ): JsonResponse {
        $category = ($this->handler)($id);
        if ($category) {
            return $this->json(
                ["message" => "Se ha eliminado la categoría de id: $id"]
            );
        } else {
            return $this->json(
                ["message" => "No existe una categoría de id: $id"],
                404
            );
        }
    }
}
