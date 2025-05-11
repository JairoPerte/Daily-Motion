<?php

namespace App\Authentication\Application\UseCase\LogIn;

class LogInCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $userAgent
    ) {}
}
