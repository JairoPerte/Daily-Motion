<?php

namespace App\Category\Application\UseCase\UpdateCategory;

class UpdateCategoryCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly int $iconNumber
    ) {}
}
