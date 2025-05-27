<?php

namespace App\Authentication\Infrastructure\Listener;

use App\Authentication\Domain\ValueObject\SessionId;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Domain\Exception\EmailNotVerifiedException;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;

class SessionVerificator
{
    public function __construct(
        private SessionRepositoryInterface $sessionRepository,
        private AuthContext $authContext
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->authContext->isVerified()) {
            throw new EmailNotVerifiedException();
        }

        $session = $this->sessionRepository->findById(new SessionId($this->authContext->getSessionId()));

        if (!$session || !$session->isValid() || $session->getUserId()->getUuid() == $this->authContext->getUserId()) {
            throw new SessionClosedException("The session has been closed from another device or is invalid.");
        }
    }
}
