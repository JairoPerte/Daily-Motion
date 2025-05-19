<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class JwtNotFoundException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Missing Json Web Token");
        $this->httpCode = 401;
    }
}
