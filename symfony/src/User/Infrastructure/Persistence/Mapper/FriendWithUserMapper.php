<?php

namespace App\User\Infrastructure\Persistence\Mapper;

use App\User\Domain\Entity\FriendWithUser;
use App\User\Infrastructure\Persistence\Entity\DoctrineFriend;
use App\User\Infrastructure\Persistence\Entity\DoctrineUser;

class FriendWithUserMapper
{
    public function __construct(
        private FriendMapper $mapper,
        private UserMapper $userMapper
    ) {}

    /**
     * @param array<int, array{0: DoctrineFriend, 1: DoctrineUser}> $arrayFriendUser
     */
    public function toDomain(array $arrayFriendUser): FriendWithUser
    {
        foreach ($arrayFriendUser as $doctrineEntity) {
            if ($doctrineEntity instanceof DoctrineUser) {
                $user = $this->userMapper->toDomain($doctrineEntity);
            } else if ($doctrineEntity instanceof DoctrineFriend) {
                $friend = $this->mapper->toDomain($doctrineEntity);
            }
        }

        return new FriendWithUser($friend, $user);
    }
}
