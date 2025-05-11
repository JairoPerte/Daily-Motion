<?php

namespace App\Authentication\Application\UseCase\LogOut;

use App\Authentication\Domain\Repository\SessionRepositoryInterface;

class LogOutHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository
    ) {}

    public function __invoke(LogOutCommand $command): void
    {
        $session = $command->session;
        $this->sessionRepository->delete($session);
    }
}
