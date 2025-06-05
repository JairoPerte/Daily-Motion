<?php

namespace App\User\Infrastructure\Controller\UserSettings;

class UserSettingsRequest
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $usertag,
        public readonly ?string $newPassword,
        public readonly string $oldPassword
    ) {}
}
