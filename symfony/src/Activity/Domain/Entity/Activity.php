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

    public static function toEntity(
        ActivityId $activityId,
        CategoryId $categoryId,
        UserId $userId,
        ActivityName $activityName,
        ActivityTimeStamps $activityTimeStamps
    ): self {
        return new self(
            activityId: $activityId,
            categoryId: $categoryId,
            userId: $userId,
            activityName: $activityName,
            activityTimeStamps: $activityTimeStamps
        );
    }

    public static function create(
        ActivityId $activityId,
        CategoryId $categoryId,
        UserId $userId,
        ActivityName $activityName,
        ActivityTimeStamps $activityTimeStamps
    ): self {
        return new self(
            activityId: $activityId,
            categoryId: $categoryId,
            userId: $userId,
            activityName: $activityName,
            activityTimeStamps: $activityTimeStamps
        );
    }

    public function getActivityId(): ActivityId
    {
        return $this->activityId;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getActivityName(): ActivityName
    {
        return $this->activityName;
    }

    public function getActivityTimeStamps(): ActivityTimeStamps
    {
        return $this->activityTimeStamps;
    }
}
