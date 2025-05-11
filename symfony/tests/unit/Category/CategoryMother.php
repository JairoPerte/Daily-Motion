<?php

namespace App\Tests\unit\Category;

use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;
use App\User\Domain\ValueObject\UserId;

class CategoryMother
{
    public static function regular(): Category
    {
        return Category::toEntity(
            new CategoryId("488b4cb7-b90b-4b16-b8bb-3daa7be18c32"),
            new UserId("4b5812bf-d97a-422d-be15-f86138b3a258"),
            new CategoryIconNumber(2),
            new CategoryName("Estudio")
        );
    }
}
