<?php

namespace App\Authentication\Application\UseCase\Sessions\RevokeAllSessions;

use App\Authentication\Application\Service\Finder\SessionFinder;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\ValueObject\UserId;

class RevokeAllSessionsHandler
{
    public function __construct(
        private SessionFinder $sessionFinder,
        private SessionRepositoryInterface $sessionRepository
    ) {}

    public function __invoke(RevokeAllSessionsCommand $command): void
    {
        $session = $this->sessionFinder->getSession(new SessionId($command->sessionId), new UserId($command->userId));

        $this->sessionRepository->revokeAllExceptOne($session);
    }
}
