<?php

namespace App\Category\Infrastructure\Controller\ListCategoryPaginated;

use App\Authentication\Infrastructure\Context\AuthContext;
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
        private ListCategoryPaginatedHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/category", name: "api_category_listPaginated", methods: ["GET"])]
    public function index(
        #[MapQueryString] ListCategoryPaginatedQuery $query
    ): JsonResponse {
        $command = new ListCategoryPaginatedCommand(
            iconNumber: $query->iconNumber,
            name: $query->name,
            page: $query->page,
            userId: $this->authContext->getUser()->getId()->getUuid()
        );

        $categories = ($this->handler)($command);

        if ($categories) {
            $response = array_map(
                fn(Category $category): ListCategoryPaginatedResponse =>
                new ListCategoryPaginatedResponse(
                    id: $category->getId()->getUuid(),
                    iconNumber: $category->getUserId()->getUuid(),
                    name: $category->getIconNumber()->getIconNumber(),
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
