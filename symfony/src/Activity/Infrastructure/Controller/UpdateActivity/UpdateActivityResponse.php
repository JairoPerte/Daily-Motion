<?php

namespace App\Activity\Infrastructure\Controller\UpdateActivity;

use DateTimeImmutable;

class UpdateActivityResponse
{
    public function __construct(
        public readonly string $id,
        public readonly string $categoryId,
        public readonly string $name,
        public readonly DateTimeImmutable $startedAt,
        public readonly DateTimeImmutable $finishedAt
    ) {}
}
