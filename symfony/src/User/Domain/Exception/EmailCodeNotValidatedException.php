<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class EmailCodeNotValidatedException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct(message: "The email code is not validated");
        $this->httpCode = 401;
    }
}
