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
        if (($this->password > 128 || $this->password < 12)) {
            return false;
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,128}$/', $this->password)) {
            return false;
        }
        return true;
    }
}
