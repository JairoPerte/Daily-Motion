<?php

namespace App\Authentication\Application\Service\Security;

use App\Authentication\Domain\Entity\Session;
use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Domain\Exception\EmailNotVerifiedException;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\Exception\UserNotFoundException;
use Exception;

class SessionValidator
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository
    ) {}

    /**
     * @throws \App\Authentication\Domain\Exception\EmailNotVerifiedException
     * @throws \App\Authentication\Domain\Exception\SessionClosedException
     */
    public function __invoke(string $userId, string $sessionId, bool $verified): Session
    {
        if (!$verified) {
            throw new EmailNotVerifiedException();
        }

        $session = $this->sessionRepository->findById(new SessionId($sessionId));

        $session->getSessionLastActivity()->hasConnected();

        $this->sessionRepository->save($session);

        if (!$session->isValid() && $session->getUserId()->getUuid() == $userId) {
            throw new SessionClosedException("The session has been closed from another device or is invalid.");
        }

        return $session;
    }
}
