<?php

namespace App\Authentication\Infrastructure\Context;

use App\User\Domain\Entity\User;
use App\Authentication\Domain\Entity\Session;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthContext
{
    public function __construct(private RequestStack $requestStack) {}

    public function getUser(): User
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('user');
    }

    public function getSession(): Session
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('session');
    }
}
