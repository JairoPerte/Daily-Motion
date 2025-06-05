<?php

namespace App\User\Application\UseCase\UserSettings;

class UserSettingsCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $sessionId,
        public readonly ?string $name,
        public readonly ?string $usertag,
        public readonly ?string $newPassword,
        public readonly string $oldPassword,
        public readonly ?string $img
    ) {}
}
