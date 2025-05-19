<?php

namespace App\Shared\Domain\Exception;

abstract class NotFoundException extends DailyMotionException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 404;
    }
}
