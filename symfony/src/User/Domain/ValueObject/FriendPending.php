<?php

namespace App\User\Domain\ValueObject;

class FriendPending
{
    public function __construct(
        private bool $pending
    ) {}

    public function isPending(): bool
    {
        return $this->pending;
    }

    public static function sendFriendRequest(): self
    {
        return new self(true);
    }

    public static function acceptFriendRequest(): self
    {
        return new self(false);
    }
}
