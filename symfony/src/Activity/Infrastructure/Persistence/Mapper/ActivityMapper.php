<?php

namespace App\Activity\Infrastructure\Persistence\Mapper;

use App\Activity\Domain\Entity\Activity;
use App\Activity\Domain\ValueObject\ActivityId;
use App\Activity\Domain\ValueObject\ActivityName;
use App\Activity\Domain\ValueObject\ActivityTimeStamps;
use App\Activity\Infrastructure\Persistence\Entity\DoctrineActivity;
use App\Category\Domain\ValueObject\CategoryId;
use App\User\Domain\ValueObject\UserId;

class ActivityMapper
{
    public function toDomain(DoctrineActivity $doctrineActivity): Activity
    {
        return Activity::toEntity(
            activityId: new ActivityId($doctrineActivity->id),
            categoryId: new CategoryId($doctrineActivity->categoryId),
            userId: new UserId($doctrineActivity->userId),
            activityName: new ActivityName($doctrineActivity->name),
            activityTimeStamps: new ActivityTimeStamps($doctrineActivity->startedAt, $doctrineActivity->finishedAt)
        );
    }

    public function toInfrastructure(Activity $activity, ?DoctrineActivity $doctrineActivity): DoctrineActivity
    {
        if (!$doctrineActivity) {
            $doctrineActivity = new DoctrineActivity();
        }

        $doctrineActivity->id = $activity->getActivityId()->getUuid();
        $doctrineActivity->categoryId = $activity->getCategoryId()->getUuid();
        $doctrineActivity->userId = $activity->getUserId()->getUuid();
        $doctrineActivity->name = $activity->getActivityName()->getString();
        $doctrineActivity->startedAt = $activity->getActivityTimeStamps()->getStartedAt();
        $doctrineActivity->finishedAt = $activity->getActivityTimeStamps()->getFinishedAt();

        return $doctrineActivity;
    }
}
