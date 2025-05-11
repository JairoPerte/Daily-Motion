<?php

namespace App\Authentication\Application\Service\Email;

use App\Authentication\Domain\Entity\Session;
use App\Shared\Domain\Mailer\AppMailerInterface;
use App\User\Domain\ValueObject\UserEmail;

class SendEmailLogIn
{
    public function __construct(
        private AppMailerInterface $mailer
    ) {}

    public function sendLogInEmail(UserEmail $userEmail, Session $session): void
    {
        if (!$userEmail->isVerified()) {
            $this->mailer->sendLogInEmail(
                userEmail: $userEmail,
                session: $session
            );
        }
    }
}
