<?php

namespace App\User\Application\UseCase\UserFriends;

use App\User\Application\UseCase\Response\PublicUserRelation;
use App\User\Domain\Criteria\FriendsLimitPerRoute;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;

class UserFriendsHandler
{
    public function __construct(
        private FriendRepositoryInterface $friendRepository,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(UserFriendsCommand $command): array
    {
        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        $friends = $this->friendRepository->findFriends(
            userId: $user->getId(),
            page: $command->page,
            limit: $command->limit
        );
        $relation = PublicUserRelation::STRANGERS;

        if ($user->getId()->getUuid() == $command->visitorId) {
            $relation = PublicUserRelation::YOURSELF;
        }
    }
}
