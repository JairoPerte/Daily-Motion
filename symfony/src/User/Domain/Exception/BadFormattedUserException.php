<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class BadFormattedUserException extends DailyMotionException
{
    public function __construct(public array $message)
    {
        parent::__construct("Los campos del usuario etán mal formateados");
        $this->httpCode = 400;
    }
}
