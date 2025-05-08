<?php

namespace App\Category\Infrastructure\Controller\ListCategoryPaginated;

use App\Category\Application\UseCase\ListCategoryPaginated\ListCategoryPaginatedCommand;
use App\Category\Application\UseCase\ListCategoryPaginated\ListCategoryPaginatedHandler;
use App\Category\Domain\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class ListCategoryPaginatedController extends AbstractController
{
    public function __construct(
        private ListCategoryPaginatedHandler $handler
    ) {}

    #[Route(path: "/api/category", name: "api_category_listPaginated", methods: ["GET"])]
    public function index(
        #[MapQueryString] ListCategoryPaginatedQuery $query
    ): JsonResponse {
        $command = new ListCategoryPaginatedCommand(
            iconNumber: $query->iconNumber,
            name: $query->name,
            page: $query->page
        );

        $categories = ($this->handler)($command);

        if ($categories) {
            $response = array_map(
                fn(Category $category) =>
                new ListCategoryPaginatedResponse(
                    $category->getId()->getUuid(),
                    $category->getUserId()->getUuid(),
                    $category->getIconNumber()->getIconNumber(),
                    $category->getName()->getName()
                ),
                $categories
            );

            return $this->json($response);
        } else {
            return $this->json(
                ["message" => "No hay datos para esa página"],
                404
            );
        }
    }
}
