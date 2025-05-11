<?php

namespace App\Authentication\Infrastructure\Persistence\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Authentication\Domain\Entity\Session;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Infrastructure\Persistence\Mapper\SessionMapper;
use App\Authentication\Infrastructure\Persistence\Entity\DoctrineSession;

class DoctrineSessionRepository implements SessionRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private SessionMapper $mapper
    ) {}

    public function delete(Session $session): void
    {
        $doctrineSession = $this->em->getRepository(DoctrineSession::class)->find($session->getId()->getUuid());
        if ($doctrineSession) {
            $this->em->remove($doctrineSession);
            $this->em->flush();
        }
    }

    public function save(Session $session): void
    {
        $doctrineSession = $this->em->getRepository(DoctrineSession::class)->find($session->getId()->getUuid());
        $this->em->persist($this->mapper->toInfrastructure($session, $doctrineSession));
        $this->em->flush();
    }

    public function revokeAllExceptOne(Session $sessionNotRevoked): void
    {
        $this->em->getConnection()->executeStatement(
            sql: 'UPDATE session 
                    SET revoked = true 
                    WHERE user_id = :userId 
                    AND id != :sessionId',
            params: [
                'userId' => $sessionNotRevoked->getUserId()->getUuid(),
                'sessionId' => $sessionNotRevoked->getId()->getUuid()
            ]
        );
    }

    public function findById(SessionId $sessionId): ?Session
    {
        $doctrineSession = $this->em->getRepository(DoctrineSession::class)->find($sessionId->getUuid());
        if ($doctrineSession) {
            return $this->mapper->toDomain($doctrineSession);
        }
        return null;
    }

    /**
     * @return Session[]
     */
    public function findAllSessionActive(): array
    {
        $now = new \DateTimeImmutable();

        $doctrineSessions = $this->em
            ->getRepository(DoctrineSession::class)
            ->createQueryBuilder('s')
            ->where('s.expiresAt < :now')
            ->andWhere('s.revoked = false')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();

        return array_map(
            fn(DoctrineSession $doctrineSession): Session => $this->mapper->toDomain($doctrineSession),
            $doctrineSessions
        );
    }
}
