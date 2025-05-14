<?php

namespace App\Category\Domain\ValueObject;

use App\Category\Domain\Exception\CategoryIconNotExistException;

class CategoryIconNumber
{
    public function __construct(
        private readonly int $iconNumber
    ) {
        if ($iconNumber <= 0) {
        }
    }

    public function getInteger(): int
    {
        return $this->iconNumber;
    }
}
