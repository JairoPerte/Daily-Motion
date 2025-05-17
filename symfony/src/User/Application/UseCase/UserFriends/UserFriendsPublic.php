<?php

namespace App\User\Application\UseCase\UserFriends;

use App\User\Application\UseCase\Common\PublicUserRelation;

class UserFriendsPublic
{
    /**
     * @param PublicFriend[] $friends
     */
    public function __construct(
        public readonly array $friends,
        public readonly PublicUserRelation $publicUserRelation
    ) {}
}
