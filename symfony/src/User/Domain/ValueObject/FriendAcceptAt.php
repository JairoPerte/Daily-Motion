<?php

namespace App\User\Domain\ValueObject;

use DateTimeImmutable;

class FriendAcceptAt
{
    public function __construct(
        private readonly DateTimeImmutable $acceptAt
    ) {}

    public static function acceptFriendRequest(): self
    {
        return new self(new DateTimeImmutable());
    }

    public function getDateTimeImmutable(): DateTimeImmutable
    {
        return $this->acceptAt;
    }
}
