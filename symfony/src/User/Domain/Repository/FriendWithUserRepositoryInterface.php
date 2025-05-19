<?php

namespace App\User\Domain\Repository;

use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Entity\FriendWithUser;

interface FriendWithUserRepositoryInterface
{
    /**
     * @return FriendWithUser[]
     */    public function findFriends(UserId $userId, int $page, int $limit): array;
}
