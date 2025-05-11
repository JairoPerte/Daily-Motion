<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

class CreateCategoryRequest
{
    public function __construct(
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
