<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;
use App\User\Domain\EntityFields\ExistingUserFields;

abstract class ExistingUserException extends DailyMotionException
{
    public function __construct(public readonly ExistingUserFields $existingUserFields)
    {
        parent::__construct("Hay campos del usuario que existen.");
        $this->httpCode = 409;
    }
}
