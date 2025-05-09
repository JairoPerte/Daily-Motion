<?php

namespace App\Tests\unit\Category\Application\UseCase\GetCategory;

use App\Category\Application\UseCase\GetCategory\GetCategoryHandler;
use PHPUnit\Framework\TestCase;
use App\Tests\unit\Category\CategoryMother;
use PHPUnit\Framework\MockObject\MockObject;
use App\Category\Domain\Repository\CategoryRepositoryInterface;

class GetCategoryHandlerTest extends TestCase
{
    private CategoryRepositoryInterface|MockObject $categoryRepository;

    public function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
    }

    public function testGetCategory(): void
    {
        $category = CategoryMother::regular();

        $this->categoryRepository->expects($this->once())
            ->method("findById")
            ->with($category->getId())
            ->willReturn($category);

        $handler = new GetCategoryHandler($this->categoryRepository);
        $response = $handler($category->getId()->getUuid());

        $this->assertEquals($category, $response);
    }

    public function testGetCategoryNotFound(): void
    {
        $category = CategoryMother::regular();

        $this->categoryRepository->expects($this->once())
            ->method("findById")
            ->with($category->getId())
            ->willReturn(null);

        $handler = new GetCategoryHandler($this->categoryRepository);
        $response = $handler($category->getId()->getUuid());

        $this->assertNull($response);
    }
}
