<?php

namespace App\User\Application\UseCase\Friends\DeclineFriendRequest;

class DeclineFriendRequestCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $usertag
    ) {}
}
