<?php

namespace App\User\Application\Service;

use App\User\Application\UseCase\Response\PublicUser;
use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Application\UseCase\Response\PublicUserRelation;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;

class UserToPublicUser
{
    public function __construct(
        private FriendRepositoryInterface $friendRepository,
        private ThrowExceptionFriendNotFound $throwExceptionFriendNotFound
    ) {}


    public function __invoke(User $user, UserId $visitorId): PublicUser
    {
        $friend = $this->friendRepository->findByUsersId($user->getId(), $visitorId);
        try {
            ($this->throwExceptionFriendNotFound)($friend);

            if ($friend->getFriendPending()->getBool()) {
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
                userName: $user->getUserName()->getString(),
                userTag: $user->getUserTag()->getString(),
                img: $user->getImg()->getString(),
                userCreatedAt: $user->getUserCreatedAt()->getDateTimeImmutable(),
                userRelation: $relacion
            );
        }
    }
}
