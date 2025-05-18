<?php

namespace App\Shared\Domain\Uuid;

use App\Shared\Domain\Exception\DailyMotionException;

class UuidException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Uuid bad formatted");
        $this->httpCode = 400;
    }
}
