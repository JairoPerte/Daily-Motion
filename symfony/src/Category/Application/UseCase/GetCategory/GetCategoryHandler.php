<?php

namespace App\Category\Application\UseCase\GetCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Exception\CategoryNotOwnedByUserException;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryId;

class GetCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws \App\Category\Domain\Exception\CategoryNotOwnedByUserException
     */
    public function __invoke(GetCategoryCommand $command): Category
    {
        $category = $this->categoryRepository->findById(new CategoryId($command->categoryId));
        if ($category->getUserId()->getUuid() == $command->userId) {
            return $category;
        } else {
            throw new CategoryNotOwnedByUserException();
        }
    }
}
