<?php

namespace App\User\Application\UseCase\GetUserLogged;

use App\Authentication\Application\Service\Security\SessionValidator;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;

class GetUserLoggedHandler
{
    public function __construct(
        private SessionValidator $sessionValidator,
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(GetUserLoggedCommand $command): User
    {
        ($this->sessionValidator)($command->userId, $command->sessionId, $command->verified);

        $user = $this->userRepository->findById(new UserId($command->userId));

        return $user;
    }
}
