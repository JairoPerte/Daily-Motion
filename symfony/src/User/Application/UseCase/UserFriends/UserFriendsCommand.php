<?php

namespace App\User\Application\UseCase\UserFriends;

class UserFriendsCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly int $page,
        public readonly int $limit
    ) {}
}
