<?php

namespace App\User\Application\UseCase\UserProfile;

use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Application\Service\UserToPublicUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Application\UseCase\UserProfile\PublicUser;
use App\User\Domain\Repository\FriendRepositoryInterface;

class UserProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private FriendRepositoryInterface $friendRepository,
        private UserToPublicUser $userToPublicUser
    ) {}

    public function __invoke(UserProfileCommand $command): PublicUser
    {
        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        $friend = $this->friendRepository->findByUsersId($user->getId(), new UserId($command->visitorId));

        return ($this->userToPublicUser)($friend, $user, $command->visitorId);
    }
}
