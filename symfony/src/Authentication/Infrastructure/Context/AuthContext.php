<?php

namespace App\Authentication\Infrastructure\Context;

use App\User\Domain\Entity\User;
use App\Authentication\Domain\Entity\Session;
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
}
