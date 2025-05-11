<?php

namespace App\Activity\Domain\ValueObject;

class ActivityName
{
    public function __construct(
        private readonly string $name
    ) {}
}
