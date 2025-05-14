<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\UserEmail;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function delete(User $user): void;

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findById(UserId $userId): User;

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findByUsertag(UserTag $userTag): User;

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findUsersBySearch(string $search, int $limit, int $page): array;

    /**
     * @throws \App\User\Domain\Exception\ExistingUserException
     */
    public function findUsertagEmailExists(string $email, string $usertag): void;

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findByEmail(string $email): User;
}
