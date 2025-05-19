<?php

namespace App\Activity\Application\UseCase\UpdateActivity;

use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Category\Domain\ValueObject\CategoryId;
use App\Activity\Domain\ValueObject\ActivityName;
use App\Activity\Domain\Exception\ActivityNotFoundException;
use App\Category\Domain\Exception\CategoryNotFoundException;
use App\Activity\Domain\Repository\ActivityRepositoryInterface;
use App\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Category\Domain\Exception\ActivityNotOwnedByUserException;

class UpdateActivityHandler
{
    public function __construct(
        private ActivityRepositoryInterface $activityRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function __invoke(UpdateActivityCommand $command): Activity
    {
        $activity = $this->activityRepository->findById(new ActivityId($command->id));

        if ($activity) {

            if ($activity->getUserId()->getUuid() == $command->userId) {

                $category = $this->categoryRepository->findById(new CategoryId($command->id));

                if ($category) {
                    $activity->update(
                        categoryId: $category->getId(),
                        activityName: new ActivityName($command->name)
                    );
                    return $activity;
                }

                throw new CategoryNotFoundException();
            }

            throw new ActivityNotOwnedByUserException();
        }
        throw new ActivityNotFoundException();
    }
}
