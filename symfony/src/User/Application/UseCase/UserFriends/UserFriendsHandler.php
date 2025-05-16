<?php

namespace App\User\Application\UseCase\UserFriends;

use App\User\Domain\Criteria\FriendsLimitPerRoute;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Domain\ValueObject\UserId;

class UserFriendsHandler
{
    public function __construct(
        private FriendRepositoryInterface $friendRepository
    ) {}

    public function __invoke(UserFriendsCommand $command): array
    {
        $friends = $this->friendRepository->findFriends(
            userId: new UserId($command->userId),
            page: $command->page,
            limit: FriendsLimitPerRoute::PRINCIPAL_LIST_FRIENDS->value
        );
    }
}
