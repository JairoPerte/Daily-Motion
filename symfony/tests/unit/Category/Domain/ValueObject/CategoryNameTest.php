<?php

namespace App\Tests\unit\Category\Domain\ValueObject;

use App\Category\Domain\ValueObject\CategoryName;
use PHPUnit\Framework\TestCase;

class CategoryNameTest extends TestCase
{
    public function testCategoryName(): void
    {
        $name = "Deporte";
        $categoryName = new CategoryName($name);
        $this->assertEquals($name, $categoryName->getName());
    }
}
