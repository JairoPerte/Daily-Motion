<?php

namespace App\Authentication\Application\UseCase\Sessions\RevokeAllSessions;

class RevokeAllSessionsCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $sessionId
    ) {}
}
