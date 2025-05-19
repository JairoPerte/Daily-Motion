<?php

namespace App\User\Infrastructure\Persistence\Repository;

use App\User\Domain\Entity\User;
use Doctrine\ORM\Query\Parameter;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Persistence\Entity\DoctrineFriend;
use App\User\Infrastructure\Persistence\Mapper\UserMapper;
use App\User\Infrastructure\Persistence\Entity\DoctrineUser;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserMapper $mapper
    ) {}

    public function save(User $user): void
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($user->getId()->getUuid());
        $this->em->persist($this->mapper->toInfrastructure($user, $doctrineUser));
        $this->em->flush();
    }

    public function delete(UserId $userId): void
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($userId->getUuid());
        $this->em->remove($doctrineUser);
        $this->em->flush();
    }

    public function findById(UserId $userId): ?User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($userId->getUuid());
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        return null;
    }

    public function findByUsertag(UserTag $userTag): ?User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(["usertag" => $userTag->getString()]);
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        return null;
    }

    public function findByEmail(string $email): ?User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(["email" => $email]);
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        return null;
    }

    /**
     * @return User[]
     */
    public function findUsersBySearch(string $search, int $limit, int $page): array
    {
        $doctrineUsersSearched = $this->em
            ->getRepository(DoctrineUser::class)
            ->createQueryBuilder('u')
            ->select('u')
            ->addSelect('(
                    SELECT COUNT(f)
                    FROM ' . DoctrineUser::class . ' f
                    WHERE (f.senderId = u.id OR f.receiverId = u.id)
                    AND f.pending = false
                ) AS HIDDEN friendsCount
            ')
            ->where('u.usertag LIKE :search')
            ->orWhere('u.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('friendsCount', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult(($page - 1) * $limit)
            ->getQuery()
            ->getResult();

        if ($doctrineUsersSearched) {
            return array_map(
                fn(DoctrineUser $doctrineUser): User => $this->mapper->toDomain($doctrineUser),
                $doctrineUsersSearched
            );
        } else {
            return [];
        }
    }

    /**
     * @return User[]
     */
    public function findUsersWith(string $email, string $usertag): array
    {
        $doctrineUsers = $this->em->getRepository(DoctrineUser::class)
            ->createQueryBuilder("u")
            ->select("u")
            ->where("u.email = :email OR u.usertag = :usertag")
            ->setParameters(new ArrayCollection([
                new Parameter('email', $email),
                new Parameter('usertag', $usertag),
            ]))
            ->getQuery()
            ->getResult();

        if ($doctrineUsers) {
            return array_map(
                fn(DoctrineUser $doctrineUser): User => $this->mapper->toDomain($doctrineUser),
                $doctrineUsers
            );
        } else {
            return [];
        }
    }

    /**
     * @return User[]
     */
    public function findFriendsPending(UserId $userId): array
    {
        $doctrineUsers = $this->em->createQueryBuilder()
            ->select('u')
            ->from(DoctrineUser::class, 'u')
            ->where(
                'EXISTS (
                SELECT 1
                    FROM ' . DoctrineFriend::class . ' f
                    WHERE f.senderId = u.id
                        AND f.receiverId = :userId
                        AND f.pending = true
                )'
            )
            ->setParameter('userId', $userId->getUuid())
            ->getQuery()
            ->getResult();

        if ($doctrineUsers) {
            return array_map(
                fn(DoctrineUser $doctrineUser): User => $this->mapper->toDomain($doctrineUser),
                $doctrineUsers
            );
        } else {
            return [];
        }
    }
}
