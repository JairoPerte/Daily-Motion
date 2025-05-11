<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function delete(User $user): void;

    public function findById(UserId $userId): ?User;

    public function findByUsertag(UserTag $userTag): ?User;

    public function findByEmail(string $email): ?User;


    public function findUsersBySearch(string $search, int $limit, int $page): ?array;
}
