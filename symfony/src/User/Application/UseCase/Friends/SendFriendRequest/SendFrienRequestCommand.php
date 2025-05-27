<?php

namespace App\User\Application\UseCase\Friends\SendFriendRequest;

class SendFrienRequestCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $usertag,
        public readonly string $sessionId,
        public readonly bool $verified
    ) {}
}
