<?php

namespace App\Authentication\Application\Service\Email;

use App\Shared\Domain\Mailer\AppMailerInterface;
use App\User\Domain\ValueObject\UserEmail;

class SendEmailVerification
{
    public function __construct(
        private AppMailerInterface $mailer
    ) {}

    public function sendEmailValidate(UserEmail $userEmail): void
    {
        if (!$userEmail->isVerified()) {
            $this->mailer->sendEmailCode($userEmail);
        }
    }
}
