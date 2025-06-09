<?php

namespace App\Authentication\Application\Service;

use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Application\UseCase\Sessions\PublicSession;

class SessionToPublicSession
{
    public function __invoke(Session $session, SessionId $sessionConnectedId): PublicSession
    {
        return new PublicSession(
            sessionId: $session->getId()->getUuid(),
            createdAt: $session->getSessionTimeStamp()->getCreatedAt(),
            userAgent: $session->getSessionUserAgent()->getString(),
            lastActivity: $session->getSessionLastActivity()->getDateTimeImmutable(),
            isThisSession: $session->getId()->getUuid() == $sessionConnectedId->getUuid()
        );
    }
}
