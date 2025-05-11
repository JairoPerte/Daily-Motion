<?php

namespace App\Activity\Domain\ValueObject;

use App\Shared\Domain\ValueObject\UuidValue;

class ActivityId extends UuidValue
{
    public function __construct(string $uuid)
    {
        parent::__construct($uuid);
    }
}
