<?php

namespace App\Tests\unit\Category\Application\UseCase\CreateCategory;

use App\Category\Application\UseCase\CreateCategory\CreateCategoryCommand;
use App\Category\Application\UseCase\CreateCategory\CreateCategoryHandler;
use App\Category\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\Category\Domain\Repository\CategoryRepositoryInterface;

class CreateCategoryHandlerTest extends TestCase
{
    private UuidGeneratorInterface|MockObject $uuidGenerator;
    private CategoryRepositoryInterface|MockObject $categoryRepository;

    public function setUp(): void
    {
        $this->uuidGenerator = $this->createMock(UuidGeneratorInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
    }

    public function testCreateCategory(): void
    {
        $id = "46d3ede4-6b1b-4fc3-b153-0ce02c674629";

        $command = new CreateCategoryCommand(
            "1e8e5b20-b79d-4ee9-90aa-9b2e42e26127",
            2,
            "Estudio"
        );

        $this->uuidGenerator->expects($this->once())
            ->method("generate")
            ->willReturn($id);

        $this->categoryRepository->expects($this->once())
            ->method("save")
            ->with($this->callback(function (Category $category) use ($command, $id): bool {
                return $category->getId()->getUuid() == $id &&
                    $category->getUserId()->getUuid() == $command->userId &&
                    $category->getIconNumber()->getIconNumber() == $command->iconNumber &&
                    $category->getName()->getName() == $command->name;
            }));

        $handler = new CreateCategoryHandler($this->uuidGenerator, $this->categoryRepository);

        $handler($command);
    }
}
