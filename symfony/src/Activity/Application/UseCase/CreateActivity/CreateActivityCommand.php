<?php

namespace App\Activity\Application\UseCase\CreateActivity;

use DateTimeImmutable;

class CreateActivityCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $categoryId,
        public readonly string $name,
        public readonly DateTimeImmutable $startedAt,
        public readonly DateTimeImmutable $finishedAt
    ) {}
}
