<?php

namespace App\Tests\unit\Category\Domain\ValueObject;

use App\Category\Domain\Exception\CategoryIconNotExistException;
use App\Category\Domain\ValueObject\CategoryIconNumber;
use PHPUnit\Framework\TestCase;

class CategoryIconNumberTest extends TestCase
{
    public function testCategoryIconNumber(): void
    {
        $iconNumber = new CategoryIconNumber(2);
        $this->assertSame(2, $iconNumber->getIconNumber());
    }

    public function testCategoryIconNumberNotExist(): void
    {
        $this->expectException(CategoryIconNotExistException::class);
        new CategoryIconNumber(0);
    }
}
