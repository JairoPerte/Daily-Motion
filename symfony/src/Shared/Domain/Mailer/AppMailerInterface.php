<?php

namespace App\Shared\Domain\Mailer;

use App\Authentication\Domain\Entity\Session;
use App\User\Domain\Entity\User;

interface AppMailerInterface
{
    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendEmailCode(User $user): void;

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendLogInEmail(User $user, Session $session): void;

    /**
     * @throws \App\Shared\Domain\Exception\MailException
     */
    public function sendFriendRequest(User $user): void;
}
