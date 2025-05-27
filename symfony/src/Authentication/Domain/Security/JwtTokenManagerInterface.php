<?php

namespace App\Authentication\Domain\Security;

use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\ValueObject\UserEmail;

interface JwtTokenManagerInterface
{
    public function createToken(UserId $userId, SessionId $sessionId, UserEmail $userEmail): string;

    public function decodeToken(string $jwt): array;
}
