<?php

namespace App\Category\Application\UseCase\GetCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\Exception\CategoryNotFoundException;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\Exception\CategoryNotOwnedByUserException;

class GetCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws \App\Category\Domain\Exception\CategoryNotOwnedByUserException
     * @throws \App\Category\Domain\Exception\CategoryNotFoundException
     */
    public function __invoke(GetCategoryCommand $command): Category
    {
        $category = $this->categoryRepository->findById(new CategoryId($command->categoryId));

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        if ($category->getUserId()->getUuid() == $command->userId) {
            return $category;
        } else {
            throw new CategoryNotOwnedByUserException();
        }
    }
}
