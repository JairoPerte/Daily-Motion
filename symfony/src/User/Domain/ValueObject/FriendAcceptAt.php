<?php

namespace App\User\Domain\ValueObject;

use DateTimeZone;
use DateTimeImmutable;

class FriendAcceptAt
{
    public function __construct(
        private readonly DateTimeImmutable $acceptAt
    ) {}

    public static function acceptFriendRequest(): self
    {
        return new self(new DateTimeImmutable("now", new DateTimeZone('Europe/Madrid')));
    }

    public function getDateTimeImmutable(): DateTimeImmutable
    {
        return $this->acceptAt;
    }
}
