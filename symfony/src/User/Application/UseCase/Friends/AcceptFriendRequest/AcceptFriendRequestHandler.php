<?php

namespace App\User\Application\UseCase\Friends\AcceptFriendRequest;

use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\Authentication\Application\Service\Security\SessionValidator;

class AcceptFriendRequestHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SessionValidator $sessionValidator,
        private FriendRepositoryInterface $friendRepository
    ) {}

    public function __invoke(AcceptFriendRequestCommand $command): void
    {
        ($this->sessionValidator)($command->id, $command->sessionId, $command->verified);

        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        if (!$user) {
            throw new UserNotFoundException();
        }

        $friend = $this->friendRepository->findByUsersId($user->getId(), new UserId($command->id));

        if (!$friend) {
            throw new FriendNotFoundException();
        }

        $friend->acceptFriendRequest();

        if ($friend->getFriendPending()->getBool()) {
            $this->friendRepository->save($friend);
        }
    }
}
