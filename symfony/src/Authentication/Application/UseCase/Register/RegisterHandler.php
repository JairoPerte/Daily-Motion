<?php

namespace App\Authentication\Application\UseCase\Register;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\UserName;
use App\Authentication\Domain\Entity\Session;
use App\User\Domain\ValueObject\UserPassword;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionUserAgent;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;
use App\Authentication\Application\Service\Security\PasswordHasher;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Application\Service\Email\SendEmailVerification;
use App\User\Application\Service\ThrowExceptionForExistingFields;

class RegisterHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SessionRepositoryInterface $sessionRepository,
        private UuidGeneratorInterface $uuidGenerator,
        private PasswordHasher $passwordHasher,
        private SendEmailVerification $sendEmailVerification,
        private JwtTokenManagerInterface $jwtTokenManager,
        private ThrowExceptionForExistingFields $throwExceptionForExistingFields
    ) {}

    public function __invoke(RegisterCommand $command): string
    {
        $user = User::create(
            userId: new UserId($this->uuidGenerator->generate()),
            userName: new UserName($command->name),
            userTag: new UserTag($command->usertag),
            email: $command->email,
            password: new UserPassword($command->password)
        );

        $user->getPassword()->setHash($this->passwordHasher->hashPassword($user->getPassword()->getString()));

        $usersWithSameFields = $this->userRepository->findUsersWith($command->email, $command->usertag);
        ($this->throwExceptionForExistingFields)($usersWithSameFields, $command->usertag, $command->email);

        $this->userRepository->save($user);

        //$this->sendEmailVerification->sendEmailValidate($user->getEmail());

        $session = Session::create(
            sessionId: new SessionId($this->uuidGenerator->generate()),
            userId: $user->getId(),
            sessionUserAgent: new SessionUserAgent($command->userAgent)
        );

        $this->sessionRepository->save($session);

        $jwt = $this->jwtTokenManager->createToken(
            userId: $user->getId(),
            sessionId: $session->getId(),
            userEmail: $user->getEmail()
        );

        return $jwt;
    }
}
