<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class EmailNotVerifiedException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("The email is not verified yet");
        $this->httpCode = 403;
    }
}
