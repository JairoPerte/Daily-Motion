<?php

namespace App\User\Application\UseCase\UserFriends;

class UserFriendsCommand
{
    public function __construct(
        public readonly string $usertag,
        public readonly int $page,
        public readonly int $limit,
        public readonly string $visitorId
    ) {}
}
