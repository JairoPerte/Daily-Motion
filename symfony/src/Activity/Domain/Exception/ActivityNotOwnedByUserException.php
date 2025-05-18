<?php

namespace App\Category\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;

class ActivityNotOwnedByUserException extends DailyMotionException
{
    public function __construct()
    {
        parent::__construct("User does not own this activity");
        $this->httpCode = 403;
    }
}
