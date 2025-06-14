<?php

namespace App\Category\Domain\ValueObject;

use App\Category\Domain\Exception\CategoryIconNotExistException;

class CategoryIconNumber
{
    public function __construct(
        private int $iconNumber
    ) {
        if ($iconNumber <= 0 || $iconNumber >= 7) {
            throw new CategoryIconNotExistException();
        }
    }

    public function getInteger(): int
    {
        return $this->iconNumber;
    }
}
