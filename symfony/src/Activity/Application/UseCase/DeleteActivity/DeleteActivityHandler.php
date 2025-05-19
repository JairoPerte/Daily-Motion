<?php

namespace App\Activity\Application\UseCase\DeleteActivity;

use App\Activity\Domain\Exception\ActivityNotFoundException;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Category\Domain\Exception\ActivityNotOwnedByUserException;

class DeleteActivityHandler
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function __invoke(DeleteActivityCommand $command): void
    {
        $activity = $this->activityRepository->findById(new ActivityId($command->id));

        if (!$activity) {
            throw new ActivityNotFoundException();
        }

        if ($activity->getUserId()->getUuid() != $command->userId) {
            throw new ActivityNotOwnedByUserException();
        }

        $this->activityRepository->delete($activity->getActivityId());
    }
}
