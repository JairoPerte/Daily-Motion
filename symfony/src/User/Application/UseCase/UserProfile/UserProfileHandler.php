<?php

namespace App\User\Application\UseCase\UserProfile;

use App\User\Domain\Entity\PublicUser;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\PublicUserRelation;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;

class UserProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private FriendRepositoryInterface $friendRepository
    ) {}

    public function __invoke(UserProfileCommand $command): PublicUser
    {
        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));
        $visitor = $this->userRepository->findById(new UserId($command->visitorId));

        try {
            $this->friendRepository->findByUsersId($user->getId(), $visitor->getId());
            $relacion = PublicUserRelation::FRIENDS;
        } catch (FriendNotFoundException $e) {
            $relacion = PublicUserRelation::STRANGERS;
            if ($user->getId()->getUuid() == $visitor->getId()->getUuid()) {
                $relacion = PublicUserRelation::YOURSELF;
            }
        } finally {
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
