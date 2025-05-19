<?php

namespace App\User\Infrastructure\Controller\UserProfile;

use DateTimeImmutable;

class UserProfileResponse
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $img,
        public readonly DateTimeImmutable $userCreatedAt,
        public readonly int $relation
    ) {}
}
