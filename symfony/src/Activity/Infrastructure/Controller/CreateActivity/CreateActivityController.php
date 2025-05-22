<?php

namespace App\Activity\Infrastructure\Controller\CreateActivity;

use App\Activity\Application\UseCase\CreateActivity\CreateActivityCommand;
use App\Activity\Application\UseCase\CreateActivity\CreateActivityHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateActivityController extends AbstractController
{
    public function __construct(
        private CreateActivityHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/v1/activity", name: "v1_activity_create", methods: ["POST"])]
    public function index(
        #[MapRequestPayload] CreateActivityRequest $request
    ): JsonResponse {
        $command = new CreateActivityCommand(
            userId: $this->authContext->getUserId(),
            categoryId: $request->categoryId,
            name: $request->name,
            startedAt: $request->startedAt,
            finishedAt: $request->finishedAt
        );

        $activity = ($this->handler)($command);

        $response = new CreateActivityRequest(
            categoryId: $activity->getActivityId()->getUuid(),
            name: $activity->getActivityName()->getString(),
            startedAt: $activity->getActivityTimeStamps()->getStartedAt(),
            finishedAt: $activity->getActivityTimeStamps()->getFinishedAt()
        );

        return $this->json($response, 201);
    }
}
