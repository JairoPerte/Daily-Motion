<?php

namespace App\Authentication\Domain\Security;

use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\ValueObject\SessionId;

interface JwtTokenManagerInterface
{
    public function createToken(UserId $userId, SessionId $sessionId): string;

    public function decodeToken(string $jwt): array;
}
