<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class LogicDailyMotionException extends DailyMotionException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 500;
    }
}
