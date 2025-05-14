<?php

namespace App\User\Domain\ValueObject;

use App\Shared\Domain\Exception\LogicDailyMotionException;
use DateTimeImmutable;
use App\User\Domain\Exception\EmailCodeNotValidatedException;

class UserEmail
{
    private function __construct(
        private readonly string $email,
        private bool $verified,
        private ?DateTimeImmutable $verifiedAt,
        private string $emailCode
    ) {
        if ($this->verified && $this->verifiedAt === null) {
            throw new LogicDailyMotionException("There is no verified at but he is verified, Bad Account");
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
            rand(1, 9999),
            4,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * @throws EmailCodeNotValidatedException
     */
    public function checkEmailCode(string $emailCode): void
    {
        if ($emailCode != $this->emailCode) {
            throw new EmailCodeNotValidatedException();
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

    public function isValid(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) && $this->email <= 255 && $this->email >= 6;
    }

    public function getString(): string
    {
        return $this->email;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getVerifiedAt(): ?DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function getEmailCode(): string
    {
        return $this->emailCode;
    }
}
