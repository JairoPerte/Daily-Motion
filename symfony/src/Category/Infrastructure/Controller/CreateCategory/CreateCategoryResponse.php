<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

class CreateCategoryResponse
{
    public function __construct(
        public readonly string $id,
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
