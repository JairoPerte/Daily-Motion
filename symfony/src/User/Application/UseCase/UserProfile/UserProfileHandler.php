<?php

namespace App\User\Application\UseCase\UserProfile;

use App\User\Domain\ValueObject\UserTag;
use App\User\Application\Service\UserToPublicUser;
use App\User\Application\UseCase\Response\PublicUser;
use App\User\Domain\Repository\UserRepositoryInterface;

class UserProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserToPublicUser $userToPublicUser
    ) {}

    public function __invoke(UserProfileCommand $command): PublicUser
    {
        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));
        return ($this->userToPublicUser)($user, $command->visitorId);
    }
}
