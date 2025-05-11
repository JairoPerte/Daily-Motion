<?php

namespace App\User\Domain\ValueObject;

class UserName
{
    public function __construct(
        private readonly string $name
    ) {}

    public function getString(): string
    {
        return $this->name;
    }
}
