<?php

namespace App\Shared\Domain\Exception;

use Exception;

abstract class NotFoundException extends Exception
{
    public readonly int $httpCode;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 404;
    }
}
