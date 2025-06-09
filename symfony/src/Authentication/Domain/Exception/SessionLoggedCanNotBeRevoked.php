<?php

namespace App\Authentication\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class SessionLoggedCanNotBeRevoked extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("You can not revoke your own session");
        $this->httpCode = 400;
    }
}
