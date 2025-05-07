<?php

namespace App\User\Domain\ValueObject;

use App\Shared\Domain\ValueObject\UuidValue;

class UserId extends UuidValue
{
    public function __construct(string $uuid)
    {
        parent::__construct($uuid);
    }
}
