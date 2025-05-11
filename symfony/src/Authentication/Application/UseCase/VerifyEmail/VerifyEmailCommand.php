<?php

namespace App\Authentication\Application\UseCase\VerifyEmail;

use App\User\Domain\Entity\User;

class VerifyEmailCommand
{
    public function __construct(
        public readonly string $code,
        public readonly User $user
    ) {}
}
