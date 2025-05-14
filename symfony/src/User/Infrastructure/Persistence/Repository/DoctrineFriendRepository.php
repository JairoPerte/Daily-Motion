<?php

namespace App\User\Infrastructure\Persistence\Repository;

use App\User\Domain\Entity\Friend;
use App\User\Domain\Exception\FriendNotFoundException;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Domain\ValueObject\FriendId;
use App\User\Infrastructure\Persistence\Entity\DoctrineFriend;
use App\User\Infrastructure\Persistence\Mapper\FriendMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;

class DoctrineFriendRepository implements FriendRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private FriendMapper $mapper
    ) {}

    public function save(Friend $friend): void
    {
        $doctrineFriend = $this->em->getRepository(DoctrineFriend::class)->find($friend->getFriendId());
        $this->em->persist($this->mapper->toInfrastructure($friend, $doctrineFriend));
        $this->em->flush();
    }

    public function delete(FriendId $friendId): void
    {
        $doctrineFriend = $this->em->getRepository(DoctrineFriend::class)->find($friendId->getUuid());
        $this->em->remove($doctrineFriend);
        $this->em->flush();
    }

    /**
     * @throws \App\User\Domain\Exception\FriendNotFoundException
     */
    public function findByUsersId(UserId $userId1, UserId $userId2): Friend
    {
        $friend = $this->em->getRepository(DoctrineFriend::class)
            ->createQueryBuilder("f")
            ->select("f")
            ->where("(f.senderId = :senderId1 AND f.receiverId = :receiverId1)")
            ->orWhere("(f.senderId = :senderId2 AND f.receiverId = :receiverId2)")
            ->setParameters(new ArrayCollection([
                new Parameter('senderId1', $userId1->getUuid()),
                new Parameter('receiverId1', $userId2->getUuid()),
                new Parameter('senderId2', $userId2->getUuid()),
                new Parameter('receiverId2', $userId1->getUuid())
            ]))
            ->getQuery()
            ->getOneOrNullResult();

        if ($friend) {
            return $this->mapper->toDomain($friend);
        }

        throw new FriendNotFoundException();
    }

    public function findFriends(UserId $userId, int $page, int $limit): array
    {
        $doctrineFriends = $this->em->getRepository(DoctrineFriend::class)
            ->createQueryBuilder("f")
            ->select("f")
            ->where("f.senderId = :userId OR f.receiverId = :userId")
            ->andWhere("f.pending = false")
            ->setParameter("userId", $userId->getUuid())
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();

        if ($doctrineFriends) {
            return array_map(
                fn(DoctrineFriend $doctrineFriend): Friend => $this->mapper->toDomain($doctrineFriend),
                $doctrineFriends
            );
        } else {
            return [];
        }
    }

    public function countFriends(UserId $userId): int
    {
        $doctrineFriends = $this->em->getRepository(DoctrineFriend::class)
            ->createQueryBuilder("f")
            ->select("COUNT(f)")
            ->where("f.senderId = :userId OR f.receiverId = :userId")
            ->andWhere("f.pending = false")
            ->setParameter("userId", $userId->getUuid())
            ->getQuery()
            ->getSingleScalarResult();
        return $doctrineFriends;
    }
}
