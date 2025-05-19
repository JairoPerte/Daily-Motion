<?php

namespace App\Authentication\Application\UseCase\LogOut;

use App\Authentication\Domain\Entity\Session;

class LogOutCommand
{
    public function __construct(
        public readonly string $sessionId
    ) {}
}
