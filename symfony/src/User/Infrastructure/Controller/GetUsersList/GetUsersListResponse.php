<?php

namespace App\User\Infrastructure\Controller\GetUsersList;

use DateTimeImmutable;

class GetUsersListResponse
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $img,
        public readonly DateTimeImmutable $createdAt
    ) {}
}
