<?php

namespace App\User\Application\UseCase\Friends\DeleteFriend;

use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\Authentication\Application\Service\Security\SessionValidator;

class DeleteFriendHandler
{
    public function __construct(
        private FriendRepositoryInterface $friendRepository,
        private SessionValidator $sessionValidator,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(DeleteFriendCommand $command): void
    {
        ($this->sessionValidator)($command->id, $command->sessionId, $command->verified);

        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        if (!$user) {
            throw new UserNotFoundException();
        }

        $friend = $this->friendRepository->findByUsersId(new UserId($command->id), $user->getId());

        if (!$friend) {
            throw new FriendNotFoundException();
        }

        $this->friendRepository->delete($friend->getFriendId());
    }
}
