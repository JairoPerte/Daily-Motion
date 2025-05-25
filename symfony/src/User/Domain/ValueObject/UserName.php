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

    public function isValid(): bool
    {
        return $this->name <= 100 && $this->name >= 5;
    }
}
