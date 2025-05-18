<?php

namespace App\User\Infrastructure\Controller\Friends\UserFriends;

use DateTimeImmutable;

class FriendResponse
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $img,
        public readonly DateTimeImmutable $friendsAcceptedAt
    ) {}
}
