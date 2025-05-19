<?php

namespace App\Authentication\Application\UseCase\VerifyEmail;

use App\Authentication\Domain\Exception\EmailAlreadyVerifiedException;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Repository\UserRepositoryInterface;

class VerifyEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(VerifyEmailCommand $command): void
    {
        $user = $this->userRepository->findById(new UserId($command->userId));

        if (!$user->getEmail()->isVerified()) {
            $user->getEmail()->checkEmailCode($command->code);
            $user->getEmail()->verify();

            $this->userRepository->save($user);
        } else {
            throw new EmailAlreadyVerifiedException();
        }
    }
}
