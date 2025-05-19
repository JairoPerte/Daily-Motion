<?php

namespace App\Activity\Application\UseCase\GetActivity;

use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\Exception\ActivityNotFoundException;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Category\Domain\Exception\ActivityNotOwnedByUserException;

class GetActivityHandler
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository
    ) {}

    public function __invoke(GetActivityCommand $command): Activity
    {
        $activity = $this->activityRepository->findById(new ActivityId($command->id));

        if ($activity) {
            if ($activity->getUserId()->getUuid() == $command->userId) {
                return $activity;
            }
            throw new ActivityNotOwnedByUserException();
        }
        throw new ActivityNotFoundException();
    }
}
