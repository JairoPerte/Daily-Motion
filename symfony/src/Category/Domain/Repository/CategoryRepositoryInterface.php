<?php

namespace App\Category\Domain\Repository;

use App\User\Domain\ValueObject\UserId;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\Criteria\CategoryCriteria;

interface CategoryRepositoryInterface
{
    public function save(Category $category): void;

    public function delete(CategoryId $categoryId): void;

    public function findById(CategoryId $id): ?Category;

    /**
     * @return Category[]
     */
    public function findByCriteriaPaginated(CategoryCriteria $criteria, UserId $userId, int $page, int $limit): array;
}
