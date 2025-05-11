<?php

namespace App\Authentication\Infrastructure\Controller\LogIn;

class LogInRequest
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}
