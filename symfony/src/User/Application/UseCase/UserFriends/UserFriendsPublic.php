<?php

namespace App\User\Application\UseCase\UserFriends;

use DateTimeImmutable;

class UserFriendsPublic
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $img,
        public readonly DateTimeImmutable $friendsAcceptedAt
    ) {}
}
