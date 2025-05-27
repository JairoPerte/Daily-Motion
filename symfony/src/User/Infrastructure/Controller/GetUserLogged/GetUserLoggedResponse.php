<?php

namespace App\User\Infrastructure\Controller\GetUserLogged;

use DateTimeImmutable;

class GetUserLoggedResponse
{
    public function __construct(
        public readonly string $name,
        public readonly string $usertag,
        public readonly string $img,
        public readonly string $email,
        public readonly DateTimeImmutable $userCreatedAt
    ) {}
}
