<?php

namespace App\Category\Domain\Entity;

use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;
use App\User\Domain\ValueObject\UserId;

class Category
{
    private function __construct(
        private CategoryId $categoryId,
        private UserId $userId,
        private CategoryIconNumber $categoryIconNumber,
        private CategoryName $categoryName
    ) {}

    public static function create(
        CategoryId $categoryId,
        UserId $userId,
        CategoryIconNumber $categoryIconNumber,
        CategoryName $categoryName
    ): self {
        return new self(
            categoryId: $categoryId,
            userId: $userId,
            categoryIconNumber: $categoryIconNumber,
            categoryName: $categoryName
        );
    }

    public static function toEntity(
        CategoryId $categoryId,
        UserId $userId,
        CategoryIconNumber $categoryIconNumber,
        CategoryName $categoryName
    ): self {
        return new self(
            categoryId: $categoryId,
            userId: $userId,
            categoryIconNumber: $categoryIconNumber,
            categoryName: $categoryName
        );
    }

    public function update(
        CategoryIconNumber $categoryIconNumber,
        CategoryName $categoryName
    ): void {
        $this->categoryIconNumber = $categoryIconNumber;
        $this->categoryName = $categoryName;
    }

    public function getId(): CategoryId
    {
        return $this->categoryId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getIconNumber(): CategoryIconNumber
    {
        return $this->categoryIconNumber;
    }

    public function getName(): CategoryName
    {
        return $this->categoryName;
    }
}
