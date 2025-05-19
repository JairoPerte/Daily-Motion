<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class EmailAlreadyVerifiedException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("The email has been already verified before");
        $this->httpCode = 409;
    }
}
