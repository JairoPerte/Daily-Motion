<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\MaxLimitException;

class UserLimitSuperpassedException extends MaxLimitException
{
    public function __construct()
    {
        parent::__construct("The limit of users has been exceeded.");
    }
}
