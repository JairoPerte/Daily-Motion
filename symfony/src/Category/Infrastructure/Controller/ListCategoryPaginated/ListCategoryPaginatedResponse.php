<?php

namespace App\Category\Infrastructure\Controller\ListCategoryPaginated;

class ListCategoryPaginatedResponse
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
