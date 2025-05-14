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

    public static function toEntity(
        FriendId $friendId,
        UserId $senderId,
        UserId $receiverId,
        FriendPending $friendPending,
        ?FriendAcceptAt $friendAcceptAt
    ): self {
        return new self(
            friendId: $friendId,
            senderId: $senderId,
            receiverId: $receiverId,
            friendPending: $friendPending,
            friendAcceptAt: $friendAcceptAt
        );
    }

    public function getFriendId(): FriendId
    {
        return $this->friendId;
    }

    public function getSenderId(): UserId
    {
        return $this->senderId;
    }

    public function getReceiverId(): UserId
    {
        return $this->receiverId;
    }

    public function getFriendPending(): FriendPending
    {
        return $this->friendPending;
    }

    public function getFriendAcceptAt(): ?FriendAcceptAt
    {
        return $this->friendAcceptAt;
    }
}
