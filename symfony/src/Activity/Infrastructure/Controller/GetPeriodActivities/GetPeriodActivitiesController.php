<?php

namespace App\Activity\Infrastructure\Controller\GetPeriodActivities;

use App\Activity\Domain\Entity\Activity;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Activity\Application\UseCase\GetPeriodActivities\GetPeriodActivitiesCommand;
use App\Activity\Application\UseCase\GetPeriodActivities\GetPeriodActivitiesHandler;

class GetPeriodActivitiesController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private GetPeriodActivitiesHandler $handler
    ) {}

    #[Route(path: "/v1/activity/period", name: "v1_activity_period", methods: ["GET"])]
    public function index(
        #[MapQueryString] GetPeriodActivitiesQuery $query
    ): JsonResponse {
        $command = new GetPeriodActivitiesCommand(
            userId: $this->authContext->getUserId(),
            startDate: $query->startDate,
            period: $query->period,
            categoryId: $query->categoryId,
            name: $query->name
        );

        $activities = ($this->handler)($command);

        $response = array_map(
            fn(Activity $activity): GetPeriodActivitiesResponse => new GetPeriodActivitiesResponse(
                id: $activity->getActivityId()->getUuid(),
                categoryId: $activity->getCategoryId()->getUuid(),
                name: $activity->getActivityName()->getString(),
                startedAt: $activity->getActivityTimeStamps()->getStartedAt(),
                finishedAt: $activity->getActivityTimeStamps()->getFinishedAt()
            ),
            $activities
        );

        return $this->json($response, 200);
    }
}
