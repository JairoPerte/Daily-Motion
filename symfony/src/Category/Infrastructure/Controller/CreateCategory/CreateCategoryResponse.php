<?php

namespace App\Category\Infrastructure\Controller\CreateCategory;

class CreateCategoryResponse
{
    public function __construct(
        public string $id,
        public string $userId,
        public int $iconNumber,
        public string $name
    ) {}
}
