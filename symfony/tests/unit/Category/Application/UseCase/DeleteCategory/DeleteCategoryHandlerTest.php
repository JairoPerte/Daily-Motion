<?php

namespace App\Tests\unit\Category\Application\UseCase\DeleteCategory;

use App\Category\Application\UseCase\DeleteCategory\DeleteCategoryHandler;
use App\Category\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\ValueObject\CategoryId;
use App\Tests\unit\Category\CategoryMother;

class DeleteCategoryHandlerTest extends TestCase
{
    private CategoryRepositoryInterface|MockObject $categoryRepository;

    public function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
    }

    public function testDeleteCategoryHandler(): void
    {
        $id = "c3f6c463-dc88-4e4e-8f19-42a9f6abf4f0";

        $category = CategoryMother::regular();

        $this->categoryRepository->expects($this->once())
            ->method("findById")
            ->with($this->callback(
                function (CategoryId $categoryId) use ($id): bool {
                    return $categoryId->getUuid() == $id;
                }
            ))
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method("delete")
            ->with($this->callback(function (Category $categoryNew) use ($category): bool {
                return $category == $categoryNew;
            }));

        $handler = new DeleteCategoryHandler($this->categoryRepository);

        $response = $handler($id);
        $this->assertEquals($category, $response);
    }

    public function testDeleteCategoryHandlerNotFound(): void
    {
        $id = "c3f6c463-dc88-4e4e-8f19-42a9f6abf4f0";

        $category = CategoryMother::regular();

        $this->categoryRepository->expects($this->once())
            ->method("findById")
            ->with($this->callback(
                function (CategoryId $categoryId) use ($id): bool {
                    return $categoryId->getUuid() == $id;
                }
            ))
            ->willReturn(null);

        $this->categoryRepository->expects($this->never())
            ->method("delete");

        $handler = new DeleteCategoryHandler($this->categoryRepository);

        $response = $handler($id);
        $this->assertNull($response);
    }
}
