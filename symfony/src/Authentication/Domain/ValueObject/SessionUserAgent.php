<?php

namespace App\Authentication\Domain\ValueObject;

class SessionUserAgent
{
    public function __construct(
        private ?string $userAgent
    ) {}

    public function getString(): ?string
    {
        return $this->userAgent;
    }
}
