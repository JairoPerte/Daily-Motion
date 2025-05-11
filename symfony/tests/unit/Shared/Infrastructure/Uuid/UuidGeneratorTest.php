<?php

namespace App\Tests\unit\Shared\Infrastructure\Uuid;

use PHPUnit\Framework\TestCase;
use App\Shared\Infrastructure\Uuid\UuidGenerator;

class UuidGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $uuidGenerator = new UuidGenerator();

        $uuid = $uuidGenerator->generate();

        $this->assertIsString($uuid);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        );
    }
}
