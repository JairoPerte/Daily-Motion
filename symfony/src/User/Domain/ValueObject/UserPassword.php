<?php

namespace App\User\Domain\ValueObject;

class UserPassword
{
    public function __construct(
        private string $password
    ) {}

    public function getString(): string
    {
        return $this->password;
    }

    public function setHash(string $hash): void
    {
        $this->password = $hash;
    }

    public function isValid(): bool
    {
        if (strlen($this->password) > 128 || strlen($this->password) < 12) {
            return false;
        }
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,128}$/', $this->password);
    }
}
