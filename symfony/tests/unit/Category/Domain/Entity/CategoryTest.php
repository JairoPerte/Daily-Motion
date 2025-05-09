<?php

namespace App\Tests\unit\Category\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\User\Domain\ValueObject\UserId;
use App\Category\Domain\Entity\Category;
use App\Category\Domain\ValueObject\CategoryId;
use App\Category\Domain\ValueObject\CategoryName;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use App\Category\Infrastructure\Controller\UpdateCategory\UpdateCategoryRequest;

class CategoryTest extends TestCase
{
    /**
     * @dataProvider createCategoryProvider()
     */
    public function testCreateCategory(CategoryId $id, UserId $userId, CategoryIconNumber $iconNumber, CategoryName $name): void
    {
        $category = Category::create($id, $userId, $iconNumber, $name);

        $this->assertEquals($id, $category->getId());
        $this->assertEquals($userId, $category->getUserId());
        $this->assertEquals($iconNumber, $category->getIconNumber());
        $this->assertEquals($name, $category->getName());
    }

    public function createCategoryProvider(): array
    {
        return [
            [
                "id" => new CategoryId("88c1669f-6b23-4baa-89ea-0170656944a1"),
                "userId" => new UserId("b50bfd04-849b-46a8-99d3-57777bba1440"),
                "iconNumber" => new CategoryIconNumber(1),
                "name" => new CategoryName("Ejercicio")
            ],
            [
                "id" => new CategoryId("be5e4659-b808-4c1b-b3bf-0585b5c29ab3"),
                "userId" => new UserId("b6c3aafc-54a4-454f-9706-3d30903ea17d"),
                "iconNumber" => new CategoryIconNumber(2),
                "name" => new CategoryName("Estudio")
            ],
            [
                "id" => new CategoryId("0897d019-743c-4253-84f3-9ca3f30b7af4"),
                "userId" => new UserId("3f89aed8-6908-4aad-b2c4-cb111b4b7cbf"),
                "iconNumber" => new CategoryIconNumber(3),
                "name" => new CategoryName("Ocio")
            ]
        ];
    }

    /**
     * @dataProvider updateCategoryProvider()
     */
    public function testUpdateCategory(Category $category, UpdateCategoryRequest $changes, Category $expected): void
    {
        $category->update(new CategoryIconNumber($changes->iconNumber), new CategoryName($changes->name));
        $this->assertEquals($expected, $category);
    }

    public function updateCategoryProvider(): array
    {
        return [
            [
                Category::toEntity(
                    categoryId: new CategoryId("aabb0ab6-dc0d-4cc5-af6b-00d7379c39d7"),
                    userId: new UserId("237c2293-2428-4ba7-a38e-554567563477"),
                    categoryIconNumber: new CategoryIconNumber(2),
                    categoryName: new CategoryName("Estudio")
                ),
                new UpdateCategoryRequest(
                    "Ejercicio",
                    1
                ),
                Category::toEntity(
                    categoryId: new CategoryId("aabb0ab6-dc0d-4cc5-af6b-00d7379c39d7"),
                    userId: new UserId("237c2293-2428-4ba7-a38e-554567563477"),
                    categoryIconNumber: new CategoryIconNumber(1),
                    categoryName: new CategoryName("Ejercicio")
                )
            ],
            [
                Category::toEntity(
                    categoryId: new CategoryId("aabb0ab6-dc0d-4cc5-af6b-00d7379c39d7"),
                    userId: new UserId("237c2293-2428-4ba7-a38e-554567563477"),
                    categoryIconNumber: new CategoryIconNumber(1),
                    categoryName: new CategoryName("Ejercicio")
                ),
                new UpdateCategoryRequest(
                    "Estudio",
                    2
                ),
                Category::toEntity(
                    categoryId: new CategoryId("aabb0ab6-dc0d-4cc5-af6b-00d7379c39d7"),
                    userId: new UserId("237c2293-2428-4ba7-a38e-554567563477"),
                    categoryIconNumber: new CategoryIconNumber(2),
                    categoryName: new CategoryName("Estudio")
                )
            ]
        ];
    }
}
