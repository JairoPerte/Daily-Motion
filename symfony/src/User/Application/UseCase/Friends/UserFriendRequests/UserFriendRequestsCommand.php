<?php

namespace App\User\Application\UseCase\Friends\UserFriendRequests;

class UserFriendRequestsCommand
{
    public function __construct(
        public readonly string $userId
    ) {}
}
