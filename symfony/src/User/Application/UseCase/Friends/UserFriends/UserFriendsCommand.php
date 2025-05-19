<?php

namespace App\User\Application\UseCase\Friends\UserFriends;

class UserFriendsCommand
{
    public function __construct(
        public readonly string $usertag,
        public readonly int $page,
        public readonly int $limit,
        public readonly string $visitorId
    ) {}
}
