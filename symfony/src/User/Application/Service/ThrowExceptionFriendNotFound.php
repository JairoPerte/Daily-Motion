<?php

namespace App\User\Application\Service;

use App\User\Domain\Entity\Friend;
use App\User\Domain\Exception\FriendNotFoundException;

class ThrowExceptionFriendNotFound
{
    public function __invoke(?Friend $friend): void
    {
        if (!$friend) {
            throw new FriendNotFoundException();
        }
    }
}
