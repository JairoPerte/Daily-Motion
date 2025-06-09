<?php

namespace App\Authentication\Application\UseCase\Sessions\GetSessionsList;

class GetSessionsListCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly string $sessionId
    ) {}
}
