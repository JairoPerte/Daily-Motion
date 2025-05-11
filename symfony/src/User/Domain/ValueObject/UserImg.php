<?php

namespace App\User\Domain\ValueObject;

class UserImg
{
    public function __construct(
        private readonly string $route
    ) {}

    public function getString(): string
    {
        return $this->route;
    }

    public static function imgDefault(): self
    {
        return new self("profile/default.png");
    }
}
