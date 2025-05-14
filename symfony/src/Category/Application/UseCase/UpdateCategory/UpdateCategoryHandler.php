<?php

namespace App\Category\Application\UseCase\UpdateCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Exception\CategoryNotOwnedByUserException;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;

class UpdateCategoryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * @throws \App\Category\Domain\Exception\CategoryNotOwnedByUserException
     */
    public function __invoke(UpdateCategoryCommand $command): Category
    {
        $category = $this->categoryRepository->findById(new CategoryId($command->id));
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
