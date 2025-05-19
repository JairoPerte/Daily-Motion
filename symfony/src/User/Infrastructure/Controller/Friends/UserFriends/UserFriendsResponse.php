<?php

namespace App\User\Infrastructure\Controller\Friends\UserFriends;

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
