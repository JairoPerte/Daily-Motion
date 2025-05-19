<?php

namespace App\User\Infrastructure\Persistence\Repository;

use Doctrine\ORM\Query\Parameter;
use App\User\Domain\Entity\Friend;
use App\User\Domain\ValueObject\UserId;
use Doctrine\ORM\EntityManagerInterface;
use App\User\Domain\ValueObject\FriendId;
use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\Repository\FriendRepositoryInterface;
use App\User\Infrastructure\Persistence\Mapper\FriendMapper;
use App\User\Infrastructure\Persistence\Entity\DoctrineFriend;

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

    public function findByUsersId(UserId $userId1, UserId $userId2): ?Friend
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

        return null;
    }

    public function countFriends(UserId $userId): int
    {
        $friendsNum = $this->em->getRepository(DoctrineFriend::class)
            ->createQueryBuilder("f")
            ->select("COUNT(f)")
            ->where("f.senderId = :userId OR f.receiverId = :userId")
            ->andWhere("f.pending = false")
            ->setParameter("userId", $userId->getUuid())
            ->getQuery()
            ->getSingleScalarResult();
        return $friendsNum;
    }
}
