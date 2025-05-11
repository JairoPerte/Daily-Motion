<?php

namespace App\Authentication\Infrastructure\Context;

use App\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

class BrowserContext
{
    public function __construct(private RequestStack $requestStack) {}

    public function getUserAgent(): string
    {
        return $this->requestStack->getCurrentRequest()->headers->get("User-Agent");
    }
}
