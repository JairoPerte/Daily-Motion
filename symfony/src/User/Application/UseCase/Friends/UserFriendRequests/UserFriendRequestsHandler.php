<?php

namespace App\User\Application\UseCase\Friends\UserFriendRequests;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;

class UserFriendRequestsHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @return User[]
     */
    public function __invoke(UserFriendRequestsCommand $command): array
    {
        return $this->userRepository->findFriendsPending(new UserId($command->userId));
    }
}
