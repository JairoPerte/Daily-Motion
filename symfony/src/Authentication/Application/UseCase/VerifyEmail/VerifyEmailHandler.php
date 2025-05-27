<?php

namespace App\Authentication\Application\UseCase\VerifyEmail;

use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;
use App\Authentication\Domain\Exception\EmailAlreadyVerifiedException;

class VerifyEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JwtTokenManagerInterface $jwtTokenManager
    ) {}

    public function __invoke(VerifyEmailCommand $command): string
    {
        $user = $this->userRepository->findById(new UserId($command->userId));

        if (!$user->getEmail()->isVerified()) {
            $user->getEmail()->checkEmailCode($command->code);
            $user->getEmail()->verify();

            $this->userRepository->save($user);

            return $this->jwtTokenManager->createToken(
                userId: $user->getId(),
                sessionId: new SessionId($command->sessionId),
                userEmail: $user->getEmail()
            );
        } else {
            throw new EmailAlreadyVerifiedException();
        }
    }
}
