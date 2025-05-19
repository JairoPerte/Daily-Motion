<?php

namespace App\Activity\Domain\ValueObject;

use DateTimeImmutable;

class ActivityTimeStamps
{
    public function __construct(
        private DateTimeImmutable $startedAt,
        private ?DateTimeImmutable $finishedAt
    ) {}

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): ?DateTimeImmutable
    {
        return $this->finishedAt;
    }
}
