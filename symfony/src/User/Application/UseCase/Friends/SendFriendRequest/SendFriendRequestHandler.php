<?php

namespace App\User\Application\UseCase\Friends\SendFriendRequest;

use App\User\Domain\Entity\Friend;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\FriendId;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Application\Service\SendFriendRequestEmail;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\Authentication\Application\Service\Security\SessionValidator;

class SendFriendRequestHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private FriendRepositoryInterface $friendRepository,
        private UuidGeneratorInterface $uuidGenerator,
        private SendFriendRequestEmail $sendFriendRequestEmail,
        private SessionValidator $sessionValidator
    ) {}

    public function __invoke(SendFrienRequestCommand $command): void
    {
        ($this->sessionValidator)($command->id, $command->sessionId, $command->verified);

        $user = $this->userRepository->findByUsertag(new UserTag($command->usertag));

        if (!$user) {
            throw new UserNotFoundException();
        }

        $friend = Friend::create(
            friendId: new FriendId($this->uuidGenerator->generate()),
            senderId: new UserId($command->id),
            receiverId: $user->getId()
        );

        $this->sendFriendRequestEmail->sendFriendRequest($user);

        $this->friendRepository->save($friend);
    }
}
