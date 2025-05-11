<?php

namespace App\Tests\unit\User\Domain\ValueObject;

use App\User\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function testUserId(): void
    {
        $uuid = "537fef74-7f63-4356-b6f4-58a549f13de4";
        $userId = new UserId($uuid);
        $this->assertEquals($uuid, $userId->getUuid());
    }
}
