<?php

namespace App\Authentication\Domain\ValueObject;

use DateTimeZone;
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
        $this->lastActivity = new DateTimeImmutable("now", new DateTimeZone('Europe/Madrid'));
    }

    public static function newSession(): self
    {
        return new self(new DateTimeImmutable("now", new DateTimeZone('Europe/Madrid')));
    }
}
