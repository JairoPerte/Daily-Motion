<?php

namespace App\Tests\unit\Category\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use App\Category\Domain\ValueObject\CategoryId;

class CategoryIdTest extends TestCase
{
    public function testCategoryId(): void
    {
        $uuid = "9b831a97-4b9e-411e-b3ac-9ea7890eaf15";
        $id = new CategoryId($uuid);
        $this->assertEquals($uuid, $id->getUuid());
    }
}
