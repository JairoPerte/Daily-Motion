<?php

namespace App\Category\Domain\Repository;

use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryId;

interface CategoryRepositoryInterface
{
    public function save(Category $category): void;

    public function delete(Category $id): void;

    public function findById(CategoryId $id): ?Category;

    /**
     * @return Category[]
     */
    public function findByCriteria(CategoryCriteria $criteria): ?array;
}
