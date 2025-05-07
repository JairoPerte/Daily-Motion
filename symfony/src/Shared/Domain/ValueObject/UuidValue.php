<?php

namespace App\Shared\Domain\ValueObject;

abstract class  UuidValue
{
    public function __construct(
        private readonly string $uuid
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
