<?php

namespace App\Authentication\Application\Service\Email;

use App\Authentication\Domain\Entity\Session;
use App\Shared\Domain\Mailer\AppMailerInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserEmail;

class SendEmailLogIn
{
    public function __construct(
        private AppMailerInterface $mailer
    ) {}

    public function sendLogInEmail(User $user, Session $session): void
    {
        if ($user->getEmail()->isVerified()) {
            $this->mailer->sendLogInEmail(
                user: $user,
                session: $session
            );
        }
    }
}
