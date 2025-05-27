<?php

namespace App\Authentication\Application\Service\Security;

use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Domain\Exception\EmailNotVerifiedException;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionId;

class SessionValidator
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository
    ) {}

    public function __invoke(string $userId, string $sessionId, bool $verified): void
    {
        if (!$verified) {
            throw new EmailNotVerifiedException();
        }

        $session = $this->sessionRepository->findById(new SessionId($sessionId));

        if ($session->isValid() && $session->getUserId()->getUuid() == new UserId($userId)) {
            throw new SessionClosedException("The session has been closed from another device or is invalid.");
        }
    }
}
