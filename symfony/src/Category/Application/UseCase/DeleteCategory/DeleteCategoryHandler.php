<?php

namespace App\Category\Application\UseCase\DeleteCategory;

use App\Category\Domain\Exception\CategoryNotOwnedByUserException;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryId;

class DeleteCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws \App\Category\Domain\Exception\CategoryNotOwnedByUserException
     */
    public function __invoke(DeleteCategoryCommand $command): void
    {
        $category = $this->categoryRepository->findById(new CategoryId($command->categoryId));
        if ($category->getUserId()->getUuid() == $command->userId) {
            $this->categoryRepository->delete($category);
        } else {
            throw new CategoryNotOwnedByUserException();
        }
    }
}
