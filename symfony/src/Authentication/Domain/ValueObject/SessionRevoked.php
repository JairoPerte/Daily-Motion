<?php

namespace App\Authentication\Domain\ValueObject;

class SessionRevoked
{
    public function __construct(
        private bool $revoked
    ) {}

    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    public static function newSession(): self
    {
        return new self(false);
    }

    public function revokeSession(): void
    {
        $this->revoked = true;
    }
}
