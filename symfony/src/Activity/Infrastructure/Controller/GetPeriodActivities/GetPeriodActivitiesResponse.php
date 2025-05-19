<?php

namespace App\Activity\Infrastructure\Controller\GetPeriodActivities;

use DateTimeImmutable;

class GetPeriodActivitiesResponse
{
    public function __construct(
        public readonly string $id,
        public readonly string $categoryId,
        public readonly string $name,
        public readonly DateTimeImmutable $startedAt,
        public readonly DateTimeImmutable $finishedAt
    ) {}
}
