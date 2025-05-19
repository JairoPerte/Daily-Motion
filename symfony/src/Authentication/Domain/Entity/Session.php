<?php

namespace App\Authentication\Domain\Entity;

use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Domain\Exception\SessionNotValidException;
use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Domain\ValueObject\SessionLastActivity;
use App\Authentication\Domain\ValueObject\SessionRevoked;
use App\Authentication\Domain\ValueObject\SessionUserAgent;
use App\Authentication\Domain\ValueObject\SessionTimeStamps;

class Session
{
    private function __construct(
        private SessionId $sessionId,
        private UserId $userId,
        private SessionTimeStamps $sessionTimeStamp,
        private SessionUserAgent $sessionUserAgent,
        private SessionRevoked $sessionRevoked,
        private SessionLastActivity $sessionLastActivity
    ) {}

    public static function create(
        SessionId $sessionId,
        UserId $userId,
        SessionUserAgent $sessionUserAgent
    ): self {
        return new self(
            sessionId: $sessionId,
            userId: $userId,
            sessionTimeStamp: SessionTimeStamps::newSession(),
            sessionUserAgent: $sessionUserAgent,
            sessionRevoked: SessionRevoked::newSession(),
            sessionLastActivity: SessionLastActivity::newSession()
        );
    }

    public static function toEntity(
        SessionId $sessionId,
        UserId $userId,
        SessionTimeStamps $sessionTimeStamp,
        SessionUserAgent $sessionUserAgent,
        SessionRevoked $sessionRevoked,
        SessionLastActivity $sessionLastActivity
    ): self {
        return new self(
            sessionId: $sessionId,
            userId: $userId,
            sessionTimeStamp: $sessionTimeStamp,
            sessionUserAgent: $sessionUserAgent,
            sessionRevoked: $sessionRevoked,
            sessionLastActivity: $sessionLastActivity
        );
    }

    /**
     * @throws \App\Authentication\Domain\Exception\SessionClosedException
     */
    public function isActive(): void
    {
        if ($this->sessionTimeStamp->isExpired()) {
            throw new SessionClosedException("The session has expired, every month you need to login again for security measures.");
        }
        if ($this->sessionRevoked->isRevoked()) {
            throw new SessionClosedException("Someone has closed your session from another device.");
        }
    }

    public function isValid(): bool
    {
        if ($this->sessionTimeStamp->isExpired() || $this->sessionRevoked->isRevoked()) {
            return false;
        }
        return true;
    }

    public function getId(): SessionId
    {
        return $this->sessionId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getSessionTimeStamp(): SessionTimeStamps
    {
        return $this->sessionTimeStamp;
    }

    public function getSessionUserAgent(): SessionUserAgent
    {
        return $this->sessionUserAgent;
    }

    public function getSessionRevoked(): SessionRevoked
    {
        return $this->sessionRevoked;
    }

    public function getSessionLastActivity(): SessionLastActivity
    {
        return $this->sessionLastActivity;
    }
}
