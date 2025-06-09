<?php

namespace App\User\Domain\ValueObject;

use DateTimeZone;
use DateTimeImmutable;

class UserCreatedAt
{
    public function __construct(
        private readonly DateTimeImmutable $createdAt
    ) {}

    public function getDateTimeImmutable(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function newUser(): self
    {
        return new self(new DateTimeImmutable("now", new DateTimeZone('Europe/Madrid')));
    }
}
