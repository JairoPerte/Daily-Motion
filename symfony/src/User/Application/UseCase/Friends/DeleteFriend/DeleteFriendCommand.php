<?php

namespace App\User\Application\UseCase\Friends\DeleteFriend;

class DeleteFriendCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessionId,
        public readonly bool $verified,
        public readonly string $usertag
    ) {}
}
