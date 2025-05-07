<?php

namespace App\Category\Domain\ValueObject;

use App\Shared\Domain\ValueObject\UuidValue;

class CategoryId extends UuidValue
{
    public function __construct(
        string $uuid
    ) {
        parent::__construct($uuid);
    }
}
