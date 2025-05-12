<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

abstract class ExistingUserException extends DailyMotionException
{
    public readonly array $existingFields;

    public function __construct(string $message)
    {
        parent::__construct($message);
        $this->httpCode = 409;
    }
}
