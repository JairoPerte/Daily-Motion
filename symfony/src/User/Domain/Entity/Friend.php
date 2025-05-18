<?php

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\FriendAcceptAt;
use App\User\Domain\ValueObject\FriendId;
use App\User\Domain\ValueObject\FriendPending;
use App\User\Domain\ValueObject\UserId;
use DateTimeImmutable;

class Friend
{
    private function __construct(
        private FriendId $friendId,
        private UserId $senderId,
        private UserId $receiverId,
        private FriendPending $friendPending,
        private ?FriendAcceptAt $friendAcceptedAt
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
            friendAcceptedAt: null
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
            friendAcceptedAt: $friendAcceptAt
        );
    }

    public function acceptFriendRequest(): void
    {
        if ($this->getFriendPending()->getBool()) {
            $this->friendAcceptedAt = FriendAcceptAt::acceptFriendRequest();
            $this->friendPending = FriendPending::acceptFriendRequest();
        }
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
        return $this->friendAcceptedAt;
    }
}
