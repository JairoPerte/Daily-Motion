<?php

namespace App\Activity\Domain\Entity;

use App\Activity\Domain\ValueObject\ActivityId;
use App\Activity\Domain\ValueObject\ActivityName;
use App\Activity\Domain\ValueObject\ActivityTimeStamps;
use App\Category\Domain\ValueObject\CategoryId;
use App\User\Domain\ValueObject\UserId;

class Activity
{
    private function __construct(
        private ActivityId $activityId,
        private CategoryId $categoryId,
        private UserId $userId,
        private ActivityName $activityName,
        private ActivityTimeStamps $activityTimeStamps
    ) {}
}
