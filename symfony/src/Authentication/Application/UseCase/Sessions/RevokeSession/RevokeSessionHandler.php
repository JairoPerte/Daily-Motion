<?php

namespace App\Authentication\Application\UseCase\Sessions\RevokeSession;

use App\Authentication\Application\Service\Finder\SessionFinder;
use App\Authentication\Domain\Exception\SessionLoggedCanNotBeRevoked;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\ValueObject\UserId;

class RevokeSessionHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
        private SessionFinder $sessionFinder
    ) {}

    public function __invoke(RevokeSessionCommand $command): void
    {
        $session = $this->sessionFinder->getSession(new SessionId($command->sessionId), new UserId($command->userLoggedId));

        if ($session->getId()->getUuid() != $command->sessionLoggedId) {
            $session->getSessionRevoked()->revokeSession();

            $this->sessionRepository->save($session);
        } else {
            throw new SessionLoggedCanNotBeRevoked();
        }
    }
}
