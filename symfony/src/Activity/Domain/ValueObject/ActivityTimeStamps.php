<?php

namespace App\Activity\Domain\ValueObject;

use DateTimeImmutable;

class ActivityTimeStamps
{
    public function __construct(
        private readonly DateTimeImmutable $startedAt,
        private readonly ?DateTimeImmutable $finishedAt
    ) {}

    public function getStartedAt(): DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getFinishedAt(): DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public static function newActivity(): self
    {
        return new self(
            startedAt: new DateTimeImmutable(),
            finishedAt: null
        );
    }
}
