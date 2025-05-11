<?php

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\FriendAcceptAt;
use App\User\Domain\ValueObject\FriendPending;
use App\User\Domain\ValueObject\UserId;

class Friend
{
    private function __construct(
        private UserId $senderId,
        private UserId $receiverId,
        private FriendPending $friendPending,
        private ?FriendAcceptAt $friendAcceptAt
    ) {}

    public static function create(
        UserId $senderId,
        UserId $receiverId
    ): self {
        return new self(
            senderId: $senderId,
            receiverId: $receiverId,
            friendPending: FriendPending::sendFriendRequest(),
            friendAcceptAt: null
        );
    }
}
