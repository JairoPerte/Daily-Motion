<?php

namespace App\Authentication\Application\UseCase\Sessions\GetSessionsList;

use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Application\Service\SessionToPublicSession;
use App\Authentication\Application\UseCase\Sessions\PublicSession;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;

class GetSessionsListHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
        private UserRepositoryInterface $userRepository,
        private SessionToPublicSession $sessionToPublicSession
    ) {}

    /**
     * @return PublicSession[]
     */
    public function __invoke(GetSessionsListCommand $command): array
    {
        $sessions = $this->sessionRepository->findAllSessionActive(new UserId($command->userId));

        return array_map(
            fn(Session $session): PublicSession => ($this->sessionToPublicSession)($session, new SessionId($command->sessionId)),
            $sessions
        );
    }
}
