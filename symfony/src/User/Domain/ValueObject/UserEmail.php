<?php

namespace App\User\Domain\ValueObject;

use App\User\Domain\Exception\EmailCodeNotValidException;
use LogicException;
use DateTimeImmutable;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class UserEmail
{
    private function __construct(
        private readonly string $email,
        private bool $verified,
        private ?DateTimeImmutable $verifiedAt,
        private string $emailCode
    ) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email mal formateado");
        }

        if (strlen($email) != 4) {
            throw new InvalidArgumentException("El Código de email debe de tener 4 caracteres");
        }

        if ($this->verified && $this->verifiedAt === null) {
            throw new LogicException("Si está verificado debería tener una fecha de verificación");
        }
    }

    public static function newAccount(string $email): self
    {
        return new self(
            email: $email,
            verified: false,
            verifiedAt: null,
            emailCode: self::generateCode()
        );
    }

    private static function generateCode(): string
    {
        return str_pad(
            rand(1000, 9999),
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * @throws \App\User\Domain\Exception\EmailCodeNotValidException
     */
    public function checkEmailCode(string $emailCode): void
    {
        if ($emailCode != $this->emailCode) {
            throw new EmailCodeNotValidException("El código introducido es erróneo");
        }
    }

    public function generateNewEmailCode(): void
    {
        $this->emailCode = self::generateCode();
    }

    public function verify(): void
    {
        $this->verified = true;
        $this->verifiedAt = new DateTimeImmutable();
    }

    public static function fromExistingAccount(string $email, bool $verified, DateTimeImmutable $verifiedAt, string $emailCode): self
    {
        return new self(
            email: $email,
            verified: $verified,
            verifiedAt: $verifiedAt,
            emailCode: $emailCode
        );
    }

    public function getString(): string
    {
        return $this->email;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getVerifiedAt(): DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function getEmailCode(): string
    {
        return $this->emailCode;
    }
}
