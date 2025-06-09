<?php

namespace App\User\Application\UseCase\Friends\UserFriends;

use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Application\Service\FriendsToUserFriendsPublic;
use App\User\Application\Service\ThrowExceptionIfFriendsLimitMax;
use App\User\Domain\Repository\FriendWithUserRepositoryInterface;

class UserFriendsHandler
{
    public function __construct(
        private FriendRepositoryInterface $friendRepository,
        private UserRepositoryInterface $userRepository,
        private FriendWithUserRepositoryInterface $friendWithUserRepository,
        private FriendsToUserFriendsPublic $friendsToUserFriendsPublic,
        private ThrowExceptionIfFriendsLimitMax $throwExceptionIfFriendsLimitMax
    ) {}

    public function __invoke(UserFriendsCommand $command): UserFriendsPublic
    {
        ($this->throwExceptionIfFriendsLimitMax)($command->limit);

        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        if (!$user) {
            throw new UserNotFoundException();
        }

        $friends = $this->friendWithUserRepository->findFriends(
            userId: $user->getId(),
            page: $command->page,
            limit: $command->limit
        );

        return ($this->friendsToUserFriendsPublic)($friends, $user, $command->visitorId);
    }
}
