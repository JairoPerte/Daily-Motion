<?php

namespace App\Activity\Infrastructure\Controller\CreateActivity;

use DateTimeImmutable;

class CreateActivityRequest
{
    public function __construct(
        public readonly string $categoryId,
        public readonly string $name,
        public readonly DateTimeImmutable $startedAt,
        public readonly DateTimeImmutable $finishedAt
    ) {}
}
