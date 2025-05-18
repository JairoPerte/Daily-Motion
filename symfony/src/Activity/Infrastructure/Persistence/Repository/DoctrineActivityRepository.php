<?php

namespace App\Activity\Infrastructure\Persistence\Repository;

use DateTimeImmutable;
use App\User\Domain\ValueObject\UserId;
use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Activity\Infrastructure\Persistence\Entity\DoctrineActivity;
use App\Activity\Infrastructure\Persistence\Mapper\ActivityMapper;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineActivityRepository implements ActivityRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ActivityMapper $mapper
    ) {}

    public function save(Activity $activity): void
    {
        $doctrineActivity = $this->em->getRepository(DoctrineActivity::class)->find($activity->getActivityId()->getUuid());
        $this->em->persist($this->mapper->toInfrastructure($activity, $doctrineActivity));
        $this->em->flush();
    }

    public function delete(ActivityId $activityId): void
    {
        $doctrineActivity = $this->em->getRepository(DoctrineActivity::class)->find($activityId->getUuid());
        $this->em->remove($doctrineActivity);
        $this->em->flush();
    }

    public function findById(ActivityId $activityId): ?Activity
    {
        $doctrineActivity = $this->em->getRepository(DoctrineActivity::class)->find($activityId->getUuid());
        if ($doctrineActivity) {
            return $this->mapper->toDomain($doctrineActivity);
        }
        return null;
    }

    /**
     * @return Activity[]
     */
    public function findByActivitiesInDay(UserId $userId, DateTimeImmutable $date): array
    {
        return [];
    }

    /**
     * @return Activity[]
     */
    public function findByActivitiesInWeek(UserId $userId, DateTimeImmutable $firstDateOfWeek): array
    {
        return [];
    }
}
