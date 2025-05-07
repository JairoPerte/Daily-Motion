<?php

namespace App\Shared\Infrastructure\Uuid;

use Ramsey\Uuid\Uuid;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
