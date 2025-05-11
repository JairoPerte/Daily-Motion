<?php

namespace App\Authentication\Application\UseCase\Register;

use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Domain\ValueObject\SessionUserAgent;
use Exception;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\UserName;
use App\Authentication\Domain\Entity\Session;
use App\User\Domain\ValueObject\UserPassword;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Application\Service\Email\SendEmailVerification;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;

class RegisterHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UuidGeneratorInterface $uuidGenerator,
        private SendEmailVerification $sendEmailVerification,
        private SessionRepositoryInterface $sessionRepository,
        private JwtTokenManagerInterface $jwtTokenManager
    ) {}

    public function __invoke(RegisterCommand $command): ?string
    {
        $user = User::create(
            userId: new UserId($this->uuidGenerator->generate()),
            userName: new UserName($command->name),
            userTag: new UserTag($command->usertag),
            email: $command->email,
            password: new UserPassword($command->password)
        );

        $user->getPassword()->hashPassword();

        if ($user->getPassword()->verifyPassword($command->confirmPassword)) {

            $this->userRepository->save($user);

            try {
                $this->sendEmailVerification->sendEmailValidate($user->getEmail());
            } catch (Exception $e) {
            }

            $session = Session::create(
                sessionId: new SessionId($this->uuidGenerator->generate()),
                userId: $user->getId(),
                sessionUserAgent: new SessionUserAgent($command->userAgent)
            );

            $this->sessionRepository->save($session);

            $jwt = $this->jwtTokenManager->createToken(
                userId: $user->getId(),
                sessionId: $session->getId()
            );

            return $jwt;
        }
        return null;
    }
}
