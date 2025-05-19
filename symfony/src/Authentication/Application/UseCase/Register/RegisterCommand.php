<?php

namespace App\Authentication\Application\UseCase\Register;

class RegisterCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $password,
        public readonly string $email,
        public readonly string $userAgent
    ) {}
}
