<?php

namespace App\Category\Domain\ValueObject;

class CategoryIconNumber
{
    public function __construct(
        private readonly int $iconNumber
    ) {}

    public function getIconNumber(): int
    {
        return $this->iconNumber;
    }
}
