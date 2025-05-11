<?php

namespace App\Category\Infrastructure\Controller\UpdateCategory;

class UpdateCategoryRequest
{
    public function __construct(
        public readonly string $name,
        public readonly int $iconNumber
    ) {}
}
