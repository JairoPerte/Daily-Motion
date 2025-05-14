<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class SessionNotFoundException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("Session not found");
    }
}
