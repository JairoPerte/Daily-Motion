<?php

namespace App\Category\Infrastructure\Controller\GetCategory;

class GetCategoryResponse
{
    public function __construct(
        public readonly string $id,
        public readonly int $iconNumber,
        public readonly string $name
    ) {}
}
