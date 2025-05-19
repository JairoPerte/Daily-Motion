<?php

namespace App\Activity\Domain\Repository;

use DateTimeImmutable;
use App\User\Domain\ValueObject\UserId;
use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Activity\Domain\Criteria\ActivityCriteria;
use App\Activity\Domain\ValueObject\ActivityPeriodTime;

interface ActivityRepositoryInterface
{
    public function save(Activity $activity): void;

    public function delete(ActivityId $activityId): void;

    public function findById(ActivityId $activityId): ?Activity;

    /**
     * @return Activity[]
     */
    public function findByActivitiesByPeriodCriteria(ActivityCriteria $criteria, UserId $userId, DateTimeImmutable $startDate, ActivityPeriodTime $period): array;
}
