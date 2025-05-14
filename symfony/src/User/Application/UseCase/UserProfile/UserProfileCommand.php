<?php

namespace App\User\Application\UseCase\UserProfile;

class UserProfileCommand
{
    public function __construct(
        public readonly string $usertag,
        public readonly string $visitorId
    ) {}
}
