<?php

namespace App\User\Application\UseCase\Friends\SendFriendRequest;

class SendFrienRequestCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $usertag
    ) {}
}
