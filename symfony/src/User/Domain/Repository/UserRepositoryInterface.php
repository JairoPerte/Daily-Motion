<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\UserEmail;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function delete(UserId $userId): void;

    public function findById(UserId $userId): ?User;

    public function findByUsertag(UserTag $userTag): ?User;

    public function findByEmail(string $email): ?User;

    /**
     * @return User[]
     */
    public function findUsersBySearch(string $search, int $limit, int $page): array;

    /**
     * @return User[]
     */
    public function findUsersWith(string $email, string $usertag): array;
}
