<?php

namespace App\Authentication\Application\UseCase\DeleteUser;

use App\Authentication\Application\Service\Security\SessionValidator;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;

class DeleteUserHandler
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private SessionValidator $sessionValidator
    ) {}

    public function __invoke(DeleteUserCommand $command): void
    {
        ($this->sessionValidator)($command->idLoggedUser, $command->idSession, true);

        $this->repository->delete(new UserId($command->idLoggedUser));
    }
}
