<?php

namespace App\User\Infrastructure\Controller\UserFriends;

class UserFriendsResponse
{
    /**
     * @param FriendResponse[] $friends
     */
    public function __construct(
        public readonly array $friends,
        public readonly int $publicUserRelation
    ) {}
}
