<?php

namespace App\User\Domain\ValueObject;

class UserTag
{
    public function __construct(
        private readonly string $userTag
    ) {}

    public function getString(): string
    {
        return $this->userTag;
    }

    public function isValid(): bool
    {
        return strlen($this->userTag) >= 3 && strlen($this->userTag) <= 20;
    }
}
