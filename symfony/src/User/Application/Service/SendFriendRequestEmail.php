<?php

namespace App\User\Application\Service;

use App\User\Domain\ValueObject\UserEmail;
use App\Shared\Domain\Mailer\AppMailerInterface;

class SendFriendRequestEmail
{
    public function __construct(
        private AppMailerInterface $mailer
    ) {}

    public function sendFriendRequest(UserEmail $userEmail): void
    {
        if (!$userEmail->isVerified()) {
            $this->mailer->sendFriendRequest(
                userEmail: $userEmail
            );
        }
    }
}
