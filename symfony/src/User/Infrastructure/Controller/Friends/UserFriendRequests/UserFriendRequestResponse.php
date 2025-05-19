<?php

namespace App\User\Infrastructure\Controller\Friends\UserFriendRequests;

class UserFriendRequestResponse
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $img,
    ) {}
}
