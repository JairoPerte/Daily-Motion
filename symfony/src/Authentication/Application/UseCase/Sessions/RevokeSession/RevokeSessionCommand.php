<?php

namespace App\Authentication\Application\UseCase\Sessions\RevokeSession;

class RevokeSessionCommand
{
    public function __construct(
        public readonly string $sessionId,
        public readonly string $sessionLoggedId,
        public readonly string $userLoggedId
    ) {}
}
