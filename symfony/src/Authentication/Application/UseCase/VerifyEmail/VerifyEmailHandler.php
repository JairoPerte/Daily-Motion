<?php

namespace App\Authentication\Application\UseCase\VerifyEmail;

use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Repository\UserRepositoryInterface;

class VerifyEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @throws \App\User\Domain\Exception\EmailCodeNotValidException
     */
    public function __invoke(VerifyEmailCommand $command): void
    {
        $user = $command->user;
        if (!$user->getEmail()->isVerified()) {
            $user->getEmail()->checkEmailCode($command->code);
            $user->getEmail()->verify();

            $this->userRepository->save($user);
        }
    }
}
