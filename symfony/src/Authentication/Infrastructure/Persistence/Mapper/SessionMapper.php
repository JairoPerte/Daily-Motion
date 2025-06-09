<?php

namespace App\Authentication\Infrastructure\Persistence\Mapper;

use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Domain\ValueObject\SessionLastActivity;
use App\Authentication\Domain\ValueObject\SessionRevoked;
use App\Authentication\Domain\ValueObject\SessionTimeStamps;
use App\Authentication\Domain\ValueObject\SessionUserAgent;
use App\Authentication\Infrastructure\Persistence\Entity\DoctrineSession;
use App\User\Domain\ValueObject\UserId;

class SessionMapper
{
    public function toDomain(DoctrineSession $doctrineSession): Session
    {
        return Session::toEntity(
            sessionId: new SessionId($doctrineSession->id),
            userId: new UserId($doctrineSession->userId),
            sessionTimeStamp: new SessionTimeStamps($doctrineSession->createdAt, $doctrineSession->expiresAt),
            sessionUserAgent: new SessionUserAgent($doctrineSession->userAgent),
            sessionRevoked: new SessionRevoked($doctrineSession->revoked),
            sessionLastActivity: new SessionLastActivity($doctrineSession->lastActivity)
        );
    }

    public function toInfrastructure(Session $session, ?DoctrineSession $doctrineSession): DoctrineSession
    {
        if (!$doctrineSession) {
            $doctrineSession = new DoctrineSession();
        }

        $doctrineSession->id = $session->getId()->getUuid();
        $doctrineSession->userId = $session->getUserId()->getUuid();
        $doctrineSession->createdAt = $session->getSessionTimeStamp()->getCreatedAt();
        $doctrineSession->expiresAt = $session->getSessionTimeStamp()->getExpiresAt();
        $doctrineSession->lastActivity = $session->getSessionLastActivity()->getDateTimeImmutable();
        $doctrineSession->userAgent = $session->getSessionUserAgent()->getString();
        $doctrineSession->revoked = $session->getSessionRevoked()->isRevoked();

        return $doctrineSession;
    }
}
