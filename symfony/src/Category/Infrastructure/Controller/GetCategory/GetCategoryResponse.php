<?php

namespace App\Category\Infrastructure\Controller\GetCategory;

class GetCategoryResponse
{
    public function __construct(
        public string $id,
        public int $iconNumber,
        public string $name
    ) {}
}
