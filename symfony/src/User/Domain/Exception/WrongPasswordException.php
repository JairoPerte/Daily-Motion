<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class WrongPasswordException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Wrong password from this user");
        $this->httpCode = 401;
    }
}
