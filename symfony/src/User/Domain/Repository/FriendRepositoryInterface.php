<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\Friend;
use App\User\Domain\ValueObject\FriendId;
use App\User\Domain\ValueObject\UserId;

interface FriendRepositoryInterface
{
    public function save(Friend $friend): void;

    public function delete(FriendId $friendId): void;

    public function countFriends(UserId $userId): int;

    public function findByUsersId(UserId $userId1, UserId $userId2): ?Friend;
}
