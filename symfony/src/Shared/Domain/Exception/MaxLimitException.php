<?php

namespace App\Shared\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

abstract class MaxLimitException extends DailyMotionException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 400;
    }
}
