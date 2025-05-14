<?php

namespace App\Category\Domain\Repository;

use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryId;

interface CategoryRepositoryInterface
{
    public function save(Category $category): void;

    public function delete(CategoryId $categoryId): void;

    /**
     * @throws \App\Category\Domain\Exception\CategoryNotFoundException
     */
    public function findById(CategoryId $id): ?Category;

    /**
     * @return Category[]
     */
    public function findByCriteriaPaginated(CategoryCriteria $criteria): ?array;
}
