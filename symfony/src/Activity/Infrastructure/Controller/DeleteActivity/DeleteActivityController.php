<?php

namespace App\Activity\Infrastructure\Controller\DeleteActivity;

use App\Activity\Application\UseCase\DeleteActivity\DeleteActivityCommand;
use App\Activity\Application\UseCase\DeleteActivity\DeleteActivityHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteActivityController extends AbstractController
{
    public function __construct(
        private DeleteActivityHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/v1/activity/{id}", name: "v1_activity_delete", methods: ["DELETE"])]
    public function index(
        string $id
    ): JsonResponse {
        $command = new DeleteActivityCommand(
            id: $id,
            userId: $this->authContext->getUserId()
        );

        ($this->handler)($command);

        return $this->json(["Activity deleted successfully"], 204);
    }
}
