<?php

namespace App\Activity\Infrastructure\Persistence\Repository;

use App\Activity\Domain\Criteria\ActivityCriteria;
use DateTimeImmutable;
use App\User\Domain\ValueObject\UserId;
use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Activity\Domain\ValueObject\ActivityPeriodTime;
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
    public function findByActivitiesByPeriodCriteria(ActivityCriteria $criteria, UserId $userId, DateTimeImmutable $date, ActivityPeriodTime $period): array
    {
        $startDate = $date->setTime(0, 0, 0);

        $endOfDate = $date->setTime(23, 59, 59);

        if ($period->isWeek()) {
            $endOfDate = $endOfDate->modify("+6 days");
        }

        if ($period->isMonth()) {
            $endOfDate = $endOfDate->modify("+1 month -1 day");
        }

        $queryBuilder = $this->em->getRepository(DoctrineActivity::class)
            ->createQueryBuilder("a")
            ->select("a")
            ->where("a.userId = :userId")
            ->andWhere("a.startedAt BETWEEN :startDate AND :endOfDate")
            ->setParameter("userId", $userId)
            ->setParameter("startDate", $startDate)
            ->setParameter("endOfDate", $endOfDate);

        if ($criteria->name) {
            $queryBuilder->andWhere("name LIKE :name")
                ->setParameter("name", '%' . $criteria->name . '%');
        }

        if ($criteria->categoryId) {
            $queryBuilder->andWhere("categoryId LIKE :categoryId")
                ->setParameter("categoryId", '%' . $criteria->categoryId . '%');
        }

        $doctrineActivities = $queryBuilder->getQuery()->getResult();

        if (!$doctrineActivities) {
            return [];
        }

        return array_map(
            fn(DoctrineActivity $doctrineActivity): Activity => $this->mapper->toDomain($doctrineActivity),
            $doctrineActivities
        );
    }
}
