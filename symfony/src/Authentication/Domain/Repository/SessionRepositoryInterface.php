<?php

namespace App\Authentication\Domain\Repository;

use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionId;
use App\User\Domain\ValueObject\UserId;

interface SessionRepositoryInterface
{
    public function save(Session $session): void;

    public function delete(SessionId $sessionId): void;

    public function revokeAllExceptOne(Session $sessionNotRevoked): void;

    public function findById(SessionId $sessionId): ?Session;

    /**
     * @return Session[]
     */
    public function findAllSessionActive(UserId $userId): array;
}
