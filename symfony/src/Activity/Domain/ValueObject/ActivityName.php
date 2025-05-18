<?php

namespace App\Activity\Domain\ValueObject;

use App\Activity\Domain\Exception\ActivityNameTooLongException;

class ActivityName
{
    public function __construct(
        private string $name
    ) {
        if (strlen($name) > 100) {
            throw new ActivityNameTooLongException();
        }
    }

    public function getString(): string
    {
        return $this->name;
    }
}
