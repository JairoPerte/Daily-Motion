<?php

namespace App\Authentication\Application\UseCase\LogOut;

use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionId;

class LogOutHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository
    ) {}

    public function __invoke(LogOutCommand $command): void
    {
        $this->sessionRepository->delete(new SessionId($command->sessionId));
    }
}
