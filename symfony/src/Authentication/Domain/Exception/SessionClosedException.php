<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class SessionClosedException extends DailyMotionException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 403;
    }
}
