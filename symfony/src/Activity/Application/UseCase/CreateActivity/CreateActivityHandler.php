<?php

namespace App\Activity\Application\UseCase\CreateActivity;

use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Activity\Domain\ValueObject\ActivityName;
use App\Activity\Domain\ValueObject\ActivityTimeStamps;
use App\Category\Domain\ValueObject\CategoryId;
use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\User\Domain\ValueObject\UserId;

class CreateActivityHandler
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository,
        private UuidGeneratorInterface $uuidGenerator
    ) {}

    public function __invoke(CreateActivityCommand $command): Activity
    {
        $activity = Activity::create(
            activityId: new ActivityId($this->uuidGenerator->generate()),
            categoryId: new CategoryId($command->categoryId),
            userId: new UserId($command->userId),
            activityName: new ActivityName($command->name),
            activityTimeStamps: new ActivityTimeStamps($command->startedAt, $command->finishedAt)
        );

        $this->activityRepository->save($activity);

        return $activity;
    }
}
