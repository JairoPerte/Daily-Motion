<?php

namespace App\Category\Infrastructure\Controller\ListCategoryPaginated;

class ListCategoryPaginatedQuery
{
    public function __construct(
        public readonly ?int $iconNumber,
        public readonly ?string $name,
        public readonly int $page
    ) {}
}
