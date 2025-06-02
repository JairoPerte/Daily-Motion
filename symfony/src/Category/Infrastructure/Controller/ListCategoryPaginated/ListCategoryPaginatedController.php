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

    #[Route(path: "/v1/category", name: "v1_category_listPaginated", methods: ["GET"])]
    public function index(
        #[MapQueryString] ListCategoryPaginatedQuery $query
    ): JsonResponse {
        $command = new ListCategoryPaginatedCommand(
            iconNumber: $query->iconNumber,
            name: $query->name,
            page: $query->page,
            limit: $query->limit,
            userId: $this->authContext->getUserId()
        );

        $categories = ($this->handler)($command);

        $response = array_map(
            fn(Category $category): ListCategoryPaginatedResponse =>
            new ListCategoryPaginatedResponse(
                id: $category->getId()->getUuid(),
                iconNumber: $category->getIconNumber()->getInteger(),
                name: $category->getName()->getString(),
            ),
            $categories
        );
        return $this->json($response, 200);
    }
}
