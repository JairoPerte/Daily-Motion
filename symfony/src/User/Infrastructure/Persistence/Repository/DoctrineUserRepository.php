<?php

namespace App\User\Infrastructure\Persistence\Repository;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use App\User\Infrastructure\Persistence\Entity\DoctrineUser;
use App\User\Infrastructure\Persistence\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;

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

    public function delete(User $user): void
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($user->getId()->getUuid());
        if ($doctrineUser) {
            $this->em->remove($doctrineUser);
            $this->em->flush();
        }
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

    public function findUsersBySearch(string $search, int $limit, int $page): ?array
    {
        $doctrineUsersSearched = $this->em
            ->getRepository(DoctrineUser::class)
            ->createQueryBuilder('u')
            ->select('u.usertag', 'u.name', 'u.img')
            ->addSelect('(SELECT COUNT(*) FROM friend AS f WHERE f.senderId=u.id OR f.receiverId=u.id) AS friends')
            ->where('u.usertag LIKE :usertag')
            ->setParameter(":usertag", '%' . $search . '%')
            ->orWhere('u.name LIKE :name')
            ->setParameter(":name", '%' . $search . '%')
            ->orderBy('friends', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($page * $limit)
            ->getQuery()
            ->getResult();
        //TENER QUE HACER UN USER PUBLIC ÚNICAMENTE CON LOS VALORES PERMITIDOS ÚNICAMTE
        return $doctrineUsersSearched;
    }

    public function findByEmail(string $email): ?User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(["email" => $email]);
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        return null;
    }
}
