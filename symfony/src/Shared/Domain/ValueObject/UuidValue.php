<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Uuid\UuidException;

abstract class  UuidValue
{
    public function __construct(
        private readonly string $uuid
    ) {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid)) {
            throw new UuidException('El uuid está mal formatado');
        }
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
