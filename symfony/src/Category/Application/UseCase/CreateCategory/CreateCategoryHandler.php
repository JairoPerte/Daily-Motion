<?php

namespace App\Category\Application\UseCase\CreateCategory;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\User\Domain\ValueObject\UserId;

class CreateCategoryHandler
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function __invoke(CreateCategoryCommand $command): Category
    {
        $category = Category::create(
            categoryId: new CategoryId($this->uuidGenerator->generate()),
            userId: new UserId($command->userId),
            categoryIconNumber: new CategoryIconNumber($command->iconNumber),
            categoryName: new CategoryName($command->name)
        );

        $this->categoryRepository->save($category);

        return $category;
    }
}
