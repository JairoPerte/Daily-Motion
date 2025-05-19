<?php

namespace App\Activity\Infrastructure\Controller\GetPeriodActivities;

use DateTimeImmutable;

class GetPeriodActivitiesQuery
{
    public function __construct(
        public readonly DateTimeImmutable $startDate,
        public readonly string $period,
        public readonly ?string $categoryId,
        public readonly ?string $name
    ) {}
}
