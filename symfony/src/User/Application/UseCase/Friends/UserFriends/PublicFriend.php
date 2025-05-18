<?php

namespace App\User\Application\UseCase\Friends\UserFriends;

use App\User\Domain\ValueObject\FriendAcceptAt;
use App\User\Domain\ValueObject\UserImg;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserTag;

class PublicFriend
{
    public function __construct(
        public readonly UserName $name,
        public readonly UserTag $usertag,
        public readonly UserImg $img,
        public readonly FriendAcceptAt $friendsAcceptedAt,
    ) {}
}
