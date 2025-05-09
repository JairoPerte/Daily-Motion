<?php

namespace App\Tests\unit\Category\Application\UseCase\ListCategoryPaginated;

use PHPUnit\Framework\TestCase;
use App\Tests\unit\Category\CategoryMother;
use PHPUnit\Framework\MockObject\MockObject;
use App\Category\Domain\Criteria\CategoryCriteria;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Application\UseCase\ListCategoryPaginated\ListCategoryPaginatedCommand;
use App\Category\Application\UseCase\ListCategoryPaginated\ListCategoryPaginatedHandler;


class ListCategoryPaginatedHandlerTest extends TestCase
{
    private CategoryRepositoryInterface|MockObject $categoryRepository;

    public function setUp(): void
    {
        $this->categoryRepository = $this->createMock(CategoryRepositoryInterface::class);
    }

    /**
     * @dataProvider categoryCriteriaProvider()
     */
    public function testListCategoryPaginated(CategoryCriteria $criteria): void
    {
        $command = new ListCategoryPaginatedCommand(
            $criteria->iconNumber,
            $criteria->name,
            $criteria->page
        );

        $expected = [
            CategoryMother::regular(),
            CategoryMother::regular()
        ];

        $this->categoryRepository->expects($this->once())
            ->method("findByCriteriaPaginated")
            ->with($criteria)
            ->willReturn($expected);

        $handler = new ListCategoryPaginatedHandler($this->categoryRepository);
        $response = $handler($command);

        $this->assertEquals($expected, $response);
    }

    /**
     * @dataProvider categoryCriteriaProvider()
     */
    public function testListCategoryPaginatedNotFound(CategoryCriteria $criteria): void
    {
        $command = new ListCategoryPaginatedCommand(
            $criteria->iconNumber,
            $criteria->name,
            $criteria->page
        );

        $expected = [
            CategoryMother::regular(),
            CategoryMother::regular()
        ];

        $this->categoryRepository->expects($this->once())
            ->method("findByCriteriaPaginated")
            ->with($criteria)
            ->willReturn(null);

        $handler = new ListCategoryPaginatedHandler($this->categoryRepository);
        $response = $handler($command);
        $this->assertNull($response);
    }

    public function categoryCriteriaProvider(): array
    {
        return [
            [
                new CategoryCriteria(
                    2,
                    null,
                    1
                )
            ],
            [
                new CategoryCriteria(
                    null,
                    "Deporte",
                    2
                )
            ],
            [
                new CategoryCriteria(
                    null,
                    null,
                    3
                )
            ]
        ];
    }
}
