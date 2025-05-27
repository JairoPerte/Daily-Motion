<?php

namespace App\Authentication\Infrastructure\Context;

use Symfony\Component\HttpFoundation\RequestStack;

class AuthContext
{
    public function __construct(private RequestStack $requestStack) {}

    public function getUserId(): string
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('userId');
    }

    public function getSessionId(): string
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('sessionId');
    }

    public function isVerified(): bool
    {
        return (bool) $this->requestStack->getCurrentRequest()->attributes->get('verified');
    }
}
