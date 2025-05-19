<?php

namespace App\Activity\Domain\Repository;

use App\User\Domain\ValueObject\UserId;
use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\ValueObject\ActivityId;
use DateTimeImmutable;

interface ActivityRepositoryInterface
{
    public function save(Activity $activity): void;

    public function delete(ActivityId $activityId): void;

    public function findById(ActivityId $activityId): ?Activity;

    /**
     * @return Activity[]
     */
    public function findByActivitiesInDay(UserId $userId, DateTimeImmutable $date): array;

    /**
     * @return Activity[]
     */
    public function findByActivitiesInWeek(UserId $userId, DateTimeImmutable $firstDayOfWeek): array;
}
