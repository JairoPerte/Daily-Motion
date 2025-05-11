<?php

namespace App\Authentication\Domain\ValueObject;

use DateTimeImmutable;

class SessionLastActivity
{
    public function __construct(
        private DateTimeImmutable $lastActivity
    ) {}

    public function getDateTimeImmutable(): DateTimeImmutable
    {
        return $this->lastActivity;
    }

    public function hasConnected(): void
    {
        $this->lastActivity = new DateTimeImmutable();
    }

    public static function newSession(): self
    {
        return new self(new DateTimeImmutable());
    }
}
