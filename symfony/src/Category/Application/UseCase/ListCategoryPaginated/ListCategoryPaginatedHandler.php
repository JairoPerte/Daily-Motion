<?php

namespace App\Category\Application\UseCase\ListCategoryPaginated;

use App\Category\Application\Service\ThrowExceptionIfMaxCategoryLimit;
use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Criteria\CategoryLimitPerRoutes;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\User\Domain\ValueObject\UserId;

class ListCategoryPaginatedHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private ThrowExceptionIfMaxCategoryLimit $throwExceptionIfMaxCategoryLimit
    ) {}

    /**
     * @return Category[]
     */
    public function __invoke(ListCategoryPaginatedCommand $command): ?array
    {
        ($this->throwExceptionIfMaxCategoryLimit)($command->limit);
        return $this->categoryRepository->findByCriteriaPaginated(
            criteria: $this->toCriteria($command),
            userId: new UserId($command->userId),
            page: $command->page,
            limit: $command->limit
        );
    }

    private function toCriteria(ListCategoryPaginatedCommand $command): CategoryCriteria
    {
        return new CategoryCriteria(
            iconNumber: $command->iconNumber,
            name: $command->name,
        );
    }
}
