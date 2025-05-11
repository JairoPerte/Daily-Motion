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
}
