<?php

namespace App\Authentication\Application\UseCase\DeleteUser;

class DeleteUserCommand
{
    public function __construct(
        public readonly string $idLoggedUser,
        public readonly string $idSession
    ) {}
}
