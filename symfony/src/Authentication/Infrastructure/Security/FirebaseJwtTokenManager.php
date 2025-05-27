<?php

namespace App\Authentication\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\User\Domain\ValueObject\UserId;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;
use App\User\Domain\ValueObject\UserEmail;

class FirebaseJwtTokenManager implements JwtTokenManagerInterface
{
    public function __construct(
        private string $secretKey,
        private int $ttlSeconds
    ) {}

    public function createToken(UserId $userId, SessionId $sessionId, UserEmail $userEmail): string
    {
        $now = time();
        return JWT::encode(
            [
                'sub' => $userId->getUuid(),
                'session_id' => $sessionId->getUuid(),
                'verified' => $userEmail->isVerified(),
                "iat" => $now,
                "exp" => ($now + $this->ttlSeconds)
            ],
            $this->secretKey,
            'HS256'
        );
    }

    public function decodeToken(string $jwt): array
    {
        return (array) JWT::decode(
            $jwt,
            new Key(
                $this->secretKey,
                'HS256'
            )
        );
    }
}
