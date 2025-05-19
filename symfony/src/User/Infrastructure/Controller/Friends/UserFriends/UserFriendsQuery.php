<?php

namespace App\User\Infrastructure\Controller\Friends\UserFriends;

class UserFriendsQuery
{
    public function __construct(
        public int $page,
        public int $limit
    ) {}
}
