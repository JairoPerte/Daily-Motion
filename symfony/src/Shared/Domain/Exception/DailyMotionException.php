<?php

namespace App\Shared\Domain\Exception;

use Exception;

class DailyMotionException extends Exception
{
    public readonly int $httpCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 500;
    }
}
