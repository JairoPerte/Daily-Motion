<?php

namespace App\Activity\Domain\ValueObject;

use App\Activity\Domain\Exception\ActivityPeriodTimeNotValid;

class ActivityPeriodTime
{
    public function __construct(
        private string $period
    ) {
        if (strtolower($period) != "day" && strtolower($period) != "week" && strtolower($period) != "month") {
            throw new ActivityPeriodTimeNotValid();
        }
    }

    public function getPeriod(): string
    {
        return strtolower($this->period);
    }

    public function isDay(): bool
    {
        return strtolower($this->period) == "day";
    }

    public function isWeek(): bool
    {
        return strtolower($this->period) == "week";
    }

    public function isMonth(): bool
    {
        return strtolower($this->period) == "month";
    }
}
