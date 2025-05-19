<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\NotFoundException;

class FriendNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct("Friend not found");
    }
}
