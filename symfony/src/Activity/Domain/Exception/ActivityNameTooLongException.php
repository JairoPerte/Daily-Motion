<?php

namespace App\Activity\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class ActivityNameTooLongException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Activity name too long");
        $this->httpCode = 400;
    }
}
