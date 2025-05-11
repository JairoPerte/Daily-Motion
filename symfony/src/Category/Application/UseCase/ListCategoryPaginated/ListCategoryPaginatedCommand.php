<?php

namespace App\Category\Application\UseCase\ListCategoryPaginated;

class ListCategoryPaginatedCommand
{
    public function __construct(
        public readonly ?int $iconNumber,
        public readonly ?string $name,
        public readonly int $page,
        public readonly string $userId
    ) {}
}
