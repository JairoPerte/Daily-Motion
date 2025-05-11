<?php

namespace App\Shared\Domain\Mailer;

use App\User\Domain\ValueObject\UserEmail;
use App\Authentication\Domain\Entity\Session;
use App\Shared\Domain\Exception\MailException;

interface AppMailerInterface
{
    /**
     * @throws MailException
     */
    public function sendEmailCode(UserEmail $userEmail): void;

    /**
     * @throws MailException
     */
    public function sendLogInEmail(UserEmail $userEmail, Session $session): void;
}
