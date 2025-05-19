<?php

namespace App\Category\Application\UseCase\DeleteCategory;

class DeleteCategoryCommand
{
    public function __construct(
        public readonly string $categoryId,
        public readonly string $userId
    ) {}
}
