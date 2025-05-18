<?php

namespace App\User\Infrastructure\Controller\UserSettings;

use DateTimeImmutable;

class UserSettingsResponse
{
    public function __construct(
        private string $name,
        private string $usertag,
        private string $email,
        private string $img,
        private DateTimeImmutable $userCreatedAt
    ) {}
}
