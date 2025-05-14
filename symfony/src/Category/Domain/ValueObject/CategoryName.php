<?php

namespace App\Category\Domain\ValueObject;

class CategoryName
{
    public function __construct(
        private readonly string $name
    ) {}

    public function getString(): string
    {
        return $this->name;
    }
}
