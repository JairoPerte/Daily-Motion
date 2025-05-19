<?php

namespace App\User\Infrastructure\Persistence\Repository;

use App\User\Domain\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Domain\Entity\FriendWithUser;
use App\User\Domain\Repository\FriendWithUserRepositoryInterface;
use App\User\Infrastructure\Persistence\Entity\DoctrineUser;
use App\User\Infrastructure\Persistence\Entity\DoctrineFriend;
use App\User\Infrastructure\Persistence\Mapper\FriendWithUserMapper;

class DoctrineFriendWithUserRepository implements FriendWithUserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private FriendWithUserMapper $mapper
    ) {}

    /**
     * @return FriendWithUser[]
     */
    public function findFriends(UserId $userId, int $page, int $limit): array
    {
        $doctrineFriendsUsers = $this->em->createQueryBuilder()
            ->select('f', 'u')
            ->from(DoctrineFriend::class, 'f')
            ->innerJoin(
                DoctrineUser::class,
                'u',
                'WITH',
                '(
                    (f.senderId = :userId AND u.id = f.receiverId)
                    OR
                    (f.receiverId = :userId AND u.id = f.senderId)
                )'
            )
            ->where('f.pending = false')
            ->setParameter('userId', $userId->getUuid())
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();

        if ($doctrineFriendsUsers) {
            return array_map(
                fn(array $doctrineFriendUser): FriendWithUser => $this->mapper->toDomain($doctrineFriendUser),
                $doctrineFriendsUsers
            );
        } else {
            return [];
        }
    }
}
