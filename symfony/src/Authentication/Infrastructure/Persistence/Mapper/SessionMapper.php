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
            $doctrineSesion = new DoctrineSession();
        }

        $doctrineSesion->id = $session->getId()->getUuid();
        $doctrineSesion->userId = $session->getUserId()->getUuid();
        $doctrineSesion->createdAt = $session->getSessionTimeStamp()->getCreatedAt();
        $doctrineSesion->expiresAt = $session->getSessionTimeStamp()->getExpiresAt();
        $doctrineSesion->lastActivity = $session->getSessionLastActivity()->getDateTimeImmutable();
        $doctrineSesion->userAgent = $session->getSessionUserAgent()->getString();
        $doctrineSesion->revoked = $session->getSessionRevoked()->isRevoked();

        return $doctrineSesion;
    }
}
