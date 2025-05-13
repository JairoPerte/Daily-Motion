<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;
use App\User\Domain\EntityFields\BadFormattedUserFields;

class BadFormattedUserException extends DailyMotionException
{
    public function __construct(public readonly BadFormattedUserFields $badFormattedUserFields)
    {
        parent::__construct("Los campos del usuario etán mal formateados");
        $this->httpCode = 400;
    }
}
