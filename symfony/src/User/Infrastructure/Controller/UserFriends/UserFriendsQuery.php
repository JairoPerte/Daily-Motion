<?php

namespace App\User\Infrastructure\Controller\UserFriends;

class UserFriendsQuery
{
    public function __construct(
        public int $page,
        public int $limit
    ) {}
}
