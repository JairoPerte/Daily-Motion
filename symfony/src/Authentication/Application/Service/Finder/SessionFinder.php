<?php

namespace App\Authentication\Application\Service\Finder;

use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Domain\Exception\SessionNotFoundException;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;

class SessionFinder
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
    ) {}

    public function getSession(SessionId $sessionId, UserId $userId): Session
    {
        $session = $this->sessionRepository->findById($sessionId);
        if ($session) {
            if ($session->getUserId() == $userId) {
                return $session;
            }
            throw new SessionClosedException("Session not from user");
        }
        throw new SessionNotFoundException();
    }
}
