<?php

namespace App\Category\Application\UseCase\UpdateCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Domain\Exception\CategoryNotFoundException;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\Exception\CategoryNotOwnedByUserException;

class UpdateCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws \App\Category\Domain\Exception\CategoryNotOwnedByUserException
     * @throws \App\Category\Domain\Exception\CategoryNotFoundException
     */
    public function __invoke(UpdateCategoryCommand $command): Category
    {
        $category = $this->categoryRepository->findById(new CategoryId($command->id));

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        if ($category->getUserId()->getUuid() == $command->userId) {
            $category->update(
                categoryIconNumber: new CategoryIconNumber($command->iconNumber),
                categoryName: new CategoryName($command->name)
            );

            $this->categoryRepository->save($category);

            return $category;
        }

        throw new CategoryNotOwnedByUserException();
    }
}
