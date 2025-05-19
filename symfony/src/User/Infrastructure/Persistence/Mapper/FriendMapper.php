<?php

namespace App\User\Infrastructure\Persistence\Mapper;

use App\User\Domain\Entity\Friend;
use App\User\Domain\ValueObject\FriendAcceptAt;
use App\User\Domain\ValueObject\FriendId;
use App\User\Domain\ValueObject\FriendPending;
use App\User\Domain\ValueObject\UserId;
use App\User\Infrastructure\Persistence\Entity\DoctrineFriend;

class FriendMapper
{
    public function toInfrastructure(Friend $friend, ?DoctrineFriend $doctrineFriend): DoctrineFriend
    {
        if (!$doctrineFriend) {
            $doctrineFriend = new DoctrineFriend();
        }

        $doctrineFriend->id = $friend->getFriendId()->getUuid();
        $doctrineFriend->senderId = $friend->getSenderId()->getUuid();
        $doctrineFriend->receiverId = $friend->getReceiverId()->getUuid();
        $doctrineFriend->pending = $friend->getFriendPending()->getBool();
        $doctrineFriend->acceptedAt = $friend->getFriendAcceptAt()->getDateTimeImmutable();

        return $doctrineFriend;
    }

    public function toDomain(DoctrineFriend $doctrineFriend): Friend
    {
        return Friend::toEntity(
            friendId: new FriendId($doctrineFriend->id),
            senderId: new UserId($doctrineFriend->senderId),
            receiverId: new UserId($doctrineFriend->receiverId),
            friendPending: new FriendPending($doctrineFriend->pending),
            friendAcceptAt: new FriendAcceptAt($doctrineFriend->acceptedAt)
        );
    }
}
