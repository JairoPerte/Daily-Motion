<?php

namespace App\Authentication\Application\Service\Email;

use App\Shared\Domain\Mailer\AppMailerInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserEmail;

class SendEmailVerification
{
    public function __construct(
        private AppMailerInterface $mailer
    ) {}

    public function sendEmailValidate(User $user): void
    {
        if (!$user->getEmail()->isVerified()) {
            $this->mailer->sendEmailCode(
                user: $user
            );
        }
    }
}
