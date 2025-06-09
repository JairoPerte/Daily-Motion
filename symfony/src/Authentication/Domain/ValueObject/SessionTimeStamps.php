<?php

namespace App\Authentication\Domain\ValueObject;

use DateTimeZone;
use DateTimeImmutable;

class SessionTimeStamps
{
    public function __construct(
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $expiresAt,
    ) {}

    public function isExpired(): bool
    {
        return $this->expiresAt < new DateTimeImmutable("now", new DateTimeZone('Europe/Madrid'));
    }

    public static function newSession(): self
    {
        $now = new DateTimeImmutable("now", new DateTimeZone('Europe/Madrid'));
        return new self(
            createdAt: $now,
            expiresAt: $now->modify("+30 days")
        );
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }
}
