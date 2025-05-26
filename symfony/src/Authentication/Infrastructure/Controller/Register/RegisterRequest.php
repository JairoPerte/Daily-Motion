<?php

namespace App\Authentication\Infrastructure\Controller\Register;

class RegisterRequest
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $password,
        public readonly string $email
    ) {}
}
