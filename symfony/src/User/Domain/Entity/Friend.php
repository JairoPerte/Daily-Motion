<?php

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\FriendAcceptAt;
use App\User\Domain\ValueObject\FriendId;
use App\User\Domain\ValueObject\FriendPending;
use App\User\Domain\ValueObject\UserId;

class Friend
{
    private function __construct(
        private FriendId $friendId,
        private UserId $senderId,
        private UserId $receiverId,
        private FriendPending $friendPending,
        private ?FriendAcceptAt $friendAcceptAt
    ) {}

    public static function create(
        FriendId $friendId,
        UserId $senderId,
        UserId $receiverId
    ): self {
        return new self(
            friendId: $friendId,
            senderId: $senderId,
            receiverId: $receiverId,
            friendPending: FriendPending::sendFriendRequest(),
            friendAcceptAt: null
        );
    }
}
