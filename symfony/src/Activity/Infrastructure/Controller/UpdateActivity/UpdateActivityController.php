<?php

namespace App\Activity\Infrastructure\Controller\UpdateActivity;

use App\Activity\Application\UseCase\UpdateActivity\UpdateActivityCommand;
use App\Activity\Application\UseCase\UpdateActivity\UpdateActivityHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UpdateActivityController extends AbstractController
{
    public function __construct(
        private UpdateActivityHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/activity/{id}", name: "api_activity_update", methods: ["PUT"])]
    public function index(
        #[MapRequestPayload] UpdateActivityRequest $request,
        string $id
    ): JsonResponse {
        $command = new UpdateActivityCommand(
            id: $id,
            userId: $this->authContext->getUserId(),
            name: $request->name,
            categoryId: $request->categoryId
        );

        $activity = ($this->handler)($command);

        $response = new UpdateActivityResponse(
            id: $activity->getActivityId()->getUuid(),
            categoryId: $activity->getCategoryId()->getUuid(),
            name: $activity->getActivityName()->getString(),
            startedAt: $activity->getActivityTimeStamps()->getStartedAt(),
            finishedAt: $activity->getActivityTimeStamps()->getFinishedAt()
        );

        return $this->json($response, 201);
    }
}
