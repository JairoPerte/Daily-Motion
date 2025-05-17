<?php

namespace App\User\Application\UseCase\UserFriends;

use App\User\Application\Service\FriendsToUserFriendsPublic;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Domain\Repository\FriendWithUserRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserTag;

class UserFriendsHandler
{
    public function __construct(
        private FriendRepositoryInterface $friendRepository,
        private UserRepositoryInterface $userRepository,
        private FriendWithUserRepositoryInterface $friendWithUserRepository,
        private FriendsToUserFriendsPublic $friendsToUserFriendsPublic
    ) {}

    public function __invoke(UserFriendsCommand $command): UserFriendsPublic
    {
        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        $friends = $this->friendWithUserRepository->findFriends(
            userId: $user->getId(),
            page: $command->page,
            limit: $command->limit
        );

        return ($this->friendsToUserFriendsPublic)($friends, $user, $command->visitorId);
    }
}
