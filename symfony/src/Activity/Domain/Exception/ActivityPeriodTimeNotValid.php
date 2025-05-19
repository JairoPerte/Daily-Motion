<?php

namespace App\Activity\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class ActivityPeriodTimeNotValid extends DailyMotionException
{
    public function __construct()
    {
        $this->httpCode = 400;
        parent::__construct("Period time for the activity not valid");
    }
}
