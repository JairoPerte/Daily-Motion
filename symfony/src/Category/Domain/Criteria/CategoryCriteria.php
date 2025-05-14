<?php

namespace App\Category\Domain\Criteria;

class CategoryCriteria
{
    public function __construct(
        public readonly ?int $iconNumber,
        public readonly ?string $name,
        public readonly int $page,
        public readonly int $limit,
        public readonly string $userId
    ) {}
}
