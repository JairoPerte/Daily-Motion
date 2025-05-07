<?php

namespace App\Category\Infrastructure\Persistence\Mapper;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;
use App\Category\Infrastructure\Persistence\Entity\DoctrineCategory;
use App\User\Domain\ValueObject\UserId;

class CategoryMapper
{
    public function toDomain(DoctrineCategory $doctrineCategory): Category
    {
        return Category::toEntity(
            categoryId: new CategoryId($doctrineCategory->id),
            userId: new UserId($doctrineCategory->userId),
            categoryIconNumber: new CategoryIconNumber($doctrineCategory->iconNumber),
            categoryName: new CategoryName($doctrineCategory->name)
        );
    }

    public function toInfrastructure(Category $category, ?DoctrineCategory $doctrineCategory): DoctrineCategory
    {
        if (!$doctrineCategory) {
            $doctrineCategory = new DoctrineCategory();
        }

        $doctrineCategory->id = $category->getId()->getUuid();
        $doctrineCategory->userId = $category->getUserId()->getUuid();
        $doctrineCategory->name = $category->getName()->getName();
        $doctrineCategory->iconNumber = $category->getIconNumber()->getIconNumber();

        return $doctrineCategory;
    }
}
