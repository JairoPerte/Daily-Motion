<?php

namespace App\Category\Application\UseCase\GetCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryId;

class GetCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function __invoke(string $id): ?Category
    {
        $category = $this->categoryRepository->findById(new CategoryId($id));
        return $category;
    }
}
