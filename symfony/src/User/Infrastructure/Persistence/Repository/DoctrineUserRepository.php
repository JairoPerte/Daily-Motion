<?php

namespace App\User\Infrastructure\Persistence\Repository;

use App\User\Domain\Entity\User;
use Doctrine\ORM\Query\Parameter;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserTag;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\EntityFields\ExistingUserFields;
use App\User\Domain\Exception\ExistingUserException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Repository\UserRepositoryInterface;
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

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findById(UserId $userId): User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($userId->getUuid());
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        throw new UserNotFoundException();
    }

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findByUsertag(UserTag $userTag): User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(["usertag" => $userTag->getString()]);
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        throw new UserNotFoundException();
    }

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function findByEmail(string $email): User
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(["email" => $email]);
        if ($doctrineUser) {
            return $this->mapper->toDomain($doctrineUser);
        }
        throw new UserNotFoundException();
    }

    /**
     * @return User[]
     */
    public function findUsersBySearch(string $search, int $limit, int $page): array
    {
        // PASAR ESTOS DATOS A CRITERIA Y HACER UN USERLIMITPERROUTES
        $doctrineUsersSearched = $this->em
            ->getRepository(DoctrineUser::class)
            ->createQueryBuilder('u')
            ->select('u.usertag', 'u.name', 'u.img', 'u.createdAt')
            ->addSelect('
                (
                    SELECT COUNT(f)
                    FROM App\Entity\DoctrineFriend f
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
        //TENER QUE HACER UN USER PUBLIC ÚNICAMENTE CON LOS VALORES PERMITIDOS ÚNICAMTE
        return $doctrineUsersSearched;
    }

    /**
     * @return User[]
     */
    public function findUsersWith(string $email, string $usertag): array
    {
        $doctrineUsers = $this->em->getRepository(DoctrineUser::class)
            ->createQueryBuilder("u")
            ->select("u.usertag", "u.email")
            ->where("u.email = :email OR u.usertag = :usertag")
            ->setParameters(new ArrayCollection([
                new Parameter('email', $email),
                new Parameter('usertag', $usertag),
            ]))
            ->getQuery()
            ->getResult();

        return $doctrineUsers;
    }
}
