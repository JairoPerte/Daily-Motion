<?php

namespace App\Activity\Infrastructure\Controller\GetActivity;

use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Activity\Application\UseCase\GetActivity\GetActivityCommand;
use App\Activity\Application\UseCase\GetActivity\GetActivityHandler;

class GetActivityController extends AbstractController
{
    public function __construct(
        private GetActivityHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/v1/activity/{id}", name: "v1_activity_get", methods: ["GET"])]
    public function index(
        string $id
    ): JsonResponse {
        $command = new GetActivityCommand(
            id: $id,
            userId: $this->authContext->getUserId()
        );

        $activity = ($this->handler)($command);

        $response = new GetActivityResponse(
            $activity->getActivityId()->getUuid(),
            $activity->getCategoryId()->getUuid(),
            $activity->getActivityName()->getString(),
            $activity->getActivityTimeStamps()->getStartedAt(),
            $activity->getActivityTimeStamps()->getFinishedAt()
        );

        return $this->json($response, 200);
    }
}
