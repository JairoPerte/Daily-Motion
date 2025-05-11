<?php

namespace App\Category\Application\UseCase\ListCategoryPaginated;

use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryPaginatedHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @return Category[]
     */
    public function __invoke(ListCategoryPaginatedCommand $command): ?array
    {
        return $this->categoryRepository->findByCriteriaPaginated($this->toCriteria($command));
    }

    private function toCriteria(ListCategoryPaginatedCommand $command): CategoryCriteria
    {
        return new CategoryCriteria(
            iconNumber: $command->iconNumber,
            name: $command->name,
            page: $command->page,
            userId: $command->userId
        );
    }
}
