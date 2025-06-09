<?php

namespace App\Authentication\Application\UseCase\Sessions;

use DateTimeImmutable;

class PublicSession
{
    public function __construct(
        public readonly string $sessionId,
        public readonly DateTimeImmutable $createdAt,
        public readonly string $userAgent,
        public readonly DateTimeImmutable $lastActivity,
        public readonly bool $isThisSession
    ) {}
}
