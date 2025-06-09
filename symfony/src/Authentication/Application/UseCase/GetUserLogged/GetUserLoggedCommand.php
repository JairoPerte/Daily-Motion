<?php

namespace App\Authentication\Application\UseCase\GetUserLogged;

class GetUserLoggedCommand
{
    public function __construct(
        public readonly string $userId,
        public readonly bool $verified,
        public readonly string $sessionId
    ) {}
}
