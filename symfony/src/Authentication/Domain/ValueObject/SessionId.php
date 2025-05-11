<?php

namespace App\Authentication\Domain\ValueObject;

use App\Shared\Domain\ValueObject\UuidValue;

class SessionId extends UuidValue
{
    public function __construct(string $uuid)
    {
        parent::__construct($uuid);
    }
}
