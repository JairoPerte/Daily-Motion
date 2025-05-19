<?php

namespace App\Activity\Application\UseCase\GetPeriodActivities;

use DateTimeImmutable;

class GetPeriodActivitiesCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly DateTimeImmutable $startDate,
        public readonly string $period,
        public readonly ?string $categoryId,
        public readonly ?string $name
    ) {}
}
