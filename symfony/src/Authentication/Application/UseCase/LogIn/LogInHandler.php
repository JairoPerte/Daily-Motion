<?php

namespace App\Authentication\Application\UseCase\LogIn;

use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionUserAgent;
use App\Shared\Infrastructure\Uuid\UuidGenerator;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Exception\WrongPasswordException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Application\Service\Email\SendEmailLogIn;
use App\Authentication\Application\Service\Security\PasswordHasher;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;

class LogInHandler
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
        private UserRepositoryInterface $userRepository,
        private SendEmailLogIn $sendEmailLogIn,
        private UuidGenerator $uuidGenerator,
        private JwtTokenManagerInterface $jwtTokenManager,
        private PasswordHasher $passwordHasher
    ) {}

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function __invoke(LogInCommand $command): string
    {
        $user = $this->userRepository->findByEmail($command->email);

        if ($user) {
            if (
                $this->passwordHasher->verifyPassword(
                    $command->password,
                    $user->getPassword()->getString()
                )
            ) {
                $session = Session::create(
                    sessionId: new SessionId($this->uuidGenerator->generate()),
                    userId: $user->getId(),
                    sessionUserAgent: new SessionUserAgent($command->userAgent)
                );
                $this->sessionRepository->save($session);
                $this->sendEmailLogIn->sendLogInEmail($user, $session);
                $jwt = $this->jwtTokenManager->createToken(
                    userId: $user->getId(),
                    sessionId: $session->getId(),
                    userEmail: $user->getEmail()
                );
                return $jwt;
            }
            throw new WrongPasswordException();
        }
        throw new UserNotFoundException();
    }
}
