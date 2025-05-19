<?php

namespace App\Category\Infrastructure\Controller\UpdateCategory;

class UpdateCategoryResponse
{
    public function __construct(
        public readonly string $id,
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
