<?php

namespace App\Tests\unit\Category\Application\UseCase\UpdateCategory;

use App\Category\Application\UseCase\UpdateCategory\UpdateCategoryCommand;
use App\Category\Application\UseCase\UpdateCategory\UpdateCategoryHandler;
use App\Category\Domain\ValueObject\CategoryId;
use PHPUnit\Framework\TestCase;
use App\Tests\unit\Category\CategoryMother;
use PHPUnit\Framework\MockObject\MockObject;
use App\Category\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryHandlerTest extends TestCase
{
    private CategoryRepositoryInterface|MockObject $categoryRepository;

    public function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
    }

    public function testUpdateCategory(): void
    {
        $category = CategoryMother::regular();

        $command = new UpdateCategoryCommand(
            $category->getId()->getUuid(),
            $category->getName()->getName(),
            $category->getIconNumber()->getIconNumber()
        );

        $this->categoryRepository->expects($this->once())
            ->method("findById")
            ->with($category->getId())
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method("save")
            ->with($category);

        $handler = new UpdateCategoryHandler($this->categoryRepository);
        $response = $handler($command);

        $this->assertEquals($category, $response);
    }

    public function testUpdateCategoryNotFound(): void
    {
        $id = new CategoryId("b63750fe-a7c6-40fa-9d44-c21943d82020");
        $command = new UpdateCategoryCommand(
            $id->getUuid(),
            "Deporte",
            3
        );

        $this->categoryRepository->expects($this->once())
            ->method("findById")
            ->with($id)
            ->willReturn(null);

        $this->categoryRepository->expects($this->never())
            ->method("save");

        $handler = new UpdateCategoryHandler($this->categoryRepository);
        $response = $handler($command);

        $this->assertNull($response);
    }
}
