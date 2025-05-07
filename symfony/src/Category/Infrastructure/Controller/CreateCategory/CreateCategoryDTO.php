<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

class CreateCategoryDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
