<?php

namespace App\Activity\Application\UseCase\GetPeriodActivities;

use App\Activity\Domain\Criteria\ActivityCriteria;
use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Activity\Domain\ValueObject\ActivityPeriodTime;
use App\User\Domain\ValueObject\UserId;

class GetPeriodActivitiesHandler
{

    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    /**
     * @return Activity[]
     */
    public function __invoke(GetPeriodActivitiesCommand $command): array
    {
        return $this->activityRepository->findByActivitiesByPeriodCriteria(
            criteria: $this->toCriteria($command),
            userId: new UserId($command->userId),
            startDate: $command->startDate,
            period: new ActivityPeriodTime($command->period)
        );
    }

    private function toCriteria(GetPeriodActivitiesCommand $command): ActivityCriteria
    {
        return new ActivityCriteria(
            categoryId: $command->categoryId,
            name: $command->name
        );
    }
}
