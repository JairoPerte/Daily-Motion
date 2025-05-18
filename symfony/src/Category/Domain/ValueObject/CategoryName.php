<?php

namespace App\Category\Domain\ValueObject;

use App\Category\Domain\Exception\CategoryNameTooLongException;

class CategoryName
{
    public function __construct(
        private string $name
    ) {
        if (strlen($name) > 100) {
            throw new CategoryNameTooLongException();
        }
    }

    public function getString(): string
    {
        return $this->name;
    }
}
