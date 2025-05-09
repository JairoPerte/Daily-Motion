<?php

namespace App\Category\Domain\ValueObject;

use App\Category\Domain\Exception\CategoryIconNotExistException;

class CategoryIconNumber
{
    public function __construct(
        private readonly int $iconNumber
    ) {
        if ($iconNumber <= 0) {
            throw new CategoryIconNotExistException("No existe ese icono");
        }
    }

    public function getIconNumber(): int
    {
        return $this->iconNumber;
    }
}
