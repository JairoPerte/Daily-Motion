<?php

namespace App\Category\Application\UseCase\CreateCategory;

class CreateCategoryCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
