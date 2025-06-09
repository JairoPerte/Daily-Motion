<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\MaxLimitException;

class FriendLimitSuperpassedException extends MaxLimitException
{
    public function __construct()
    {
        parent::__construct("The limit of friends has been exceeded.");
    }
}
