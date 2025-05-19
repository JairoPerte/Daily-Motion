<?php

namespace App\User\Domain\Entity;

class FriendWithUser
{
    public function __construct(
        public readonly Friend $friend,
        public readonly User $user
    ) {}
}
