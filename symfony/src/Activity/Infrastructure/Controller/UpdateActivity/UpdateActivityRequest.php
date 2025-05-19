<?php

namespace App\Activity\Infrastructure\Controller\UpdateActivity;

class UpdateActivityRequest
{
    public function __construct(
        public readonly string $name,
        public readonly string $categoryId
    ) {}
}
