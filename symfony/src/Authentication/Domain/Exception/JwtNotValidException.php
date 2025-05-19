<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class JwtNotValidException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Invalid Token");
        $this->httpCode = 401;
    }
}
