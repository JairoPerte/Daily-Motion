<?php

namespace App\User\Application\UseCase\Friends\AcceptFriendRequest;

class AcceptFriendRequestCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessionId,
        public readonly bool $verified,
        public readonly string $usertag
    ) {}
}
