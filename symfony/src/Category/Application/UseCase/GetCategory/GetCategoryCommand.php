<?php

namespace App\Category\Application\UseCase\GetCategory;

class GetCategoryCommand
{
    public function __construct(
        public readonly string $categoryId,
        public readonly string $userId
    ) {}
}
