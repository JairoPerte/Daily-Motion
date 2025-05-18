<?php

namespace App\User\Application\UseCase\Friends\DeclineFriendRequest;

use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;

class DeclineFriendRequestHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private FriendRepositoryInterface $friendRepository
    ) {}

    public function __invoke(DeclineFriendRequestCommand $command): void
    {
        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        if (!$user) {
            throw new UserNotFoundException();
        }

        $friend = $this->friendRepository->findByUsersId($user->getId(), new UserId($command->id));

        if (!$friend) {
            throw new FriendNotFoundException();
        }

        $this->friendRepository->delete($friend->getFriendId());
    }
}
