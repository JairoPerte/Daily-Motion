<?php

namespace App\User\Domain\ValueObject;

class UserPassword
{
    public function __construct(
        private string $password
    ) {}

    public function hashPassword(): void
    {
        $this->password = password_hash($this->password, PASSWORD_ARGON2ID);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->password);
    }
}
