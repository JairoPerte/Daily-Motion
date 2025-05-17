<?php

namespace App\User\Application\Service;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Application\UseCase\UserProfile\PublicUser;
use App\User\Application\UseCase\Common\PublicUserRelation;
use App\User\Domain\Entity\Friend;

class UserToPublicUser
{
    public function __construct(
        private ThrowExceptionFriendNotFound $throwExceptionFriendNotFound
    ) {}

    public function __invoke(?Friend $friend, User $user, UserId $visitorId): PublicUser
    {
        try {
            ($this->throwExceptionFriendNotFound)($friend);

            if (!$friend->getFriendPending()->getBool()) {
                $relacion = PublicUserRelation::FRIENDS;
            } else {
                if ($friend->getSenderId()->getUuid() == $visitorId->getUuid()) {
                    $relacion = PublicUserRelation::PENDING;
                } else {
                    $relacion = PublicUserRelation::WAITING;
                }
            }
        } catch (FriendNotFoundException $e) {
            $relacion = PublicUserRelation::STRANGERS;
        } finally {
            if ($user->getId()->getUuid() == $visitorId->getUuid()) {
                $relacion = PublicUserRelation::YOURSELF;
            }
            return new PublicUser(
                name: $user->getUserName(),
                usertag: $user->getUserTag(),
                img: $user->getImg(),
                createdAt: $user->getUserCreatedAt(),
                relation: $relacion
            );
        }
    }
}
