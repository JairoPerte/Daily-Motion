<?php

namespace App\Shared\Domain\Mailer;

use App\User\Domain\ValueObject\UserEmail;
use App\Authentication\Domain\Entity\Session;

interface AppMailerInterface
{
    /**
     * @throws App\Shared\Domain\Exception\MailException
     */
    public function sendEmailCode(UserEmail $userEmail): void;

    /**
     * @throws App\Shared\Domain\Exception\MailException
     */
    public function sendLogInEmail(UserEmail $userEmail, Session $session): void;

    /**
     * @throws App\Shared\Domain\Exception\MailException
     */
    public function sendFriendRequest(UserEmail $userEmail): void;
}
