<?php

namespace App\User\Application\Service;

use App\User\Domain\ValueObject\UserEmail;
use App\Shared\Domain\Mailer\AppMailerInterface;
use App\User\Domain\Entity\User;

class SendFriendRequestEmail
{
    public function __construct(
        private AppMailerInterface $mailer
    ) {}

    public function sendFriendRequest(User $user): void
    {
        if (!$user->getEmail()->isVerified()) {
            $this->mailer->sendFriendRequest(
                user: $user
            );
        }
    }
}
