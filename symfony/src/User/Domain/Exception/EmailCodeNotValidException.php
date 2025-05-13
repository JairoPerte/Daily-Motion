<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class EmailCodeNotValidException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("El código de email es incorrecto.");
        $this->httpCode = 400;
    }
}
