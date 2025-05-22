<?php

namespace App\Shared\Domain\Exception;

use Exception;

class DailyMotionException extends Exception
{
    protected int $httpCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 500;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
