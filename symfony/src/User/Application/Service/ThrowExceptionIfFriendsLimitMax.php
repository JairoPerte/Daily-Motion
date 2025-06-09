<?php

namespace App\User\Application\Service;

use App\User\Domain\Exception\FriendLimitSuperpassedException;

class ThrowExceptionIfFriendsLimitMax
{
    public function __invoke(int $limit): void
    {
        if ($limit <= 0 || $limit > 30) {
            throw new FriendLimitSuperpassedException();
        }
    }
}
