<?php

namespace App\User\Infrastructure\Controller\GetUserLogged;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Application\UseCase\GetUserLogged\GetUserLoggedCommand;
use App\User\Application\UseCase\GetUserLogged\GetUserLoggedHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetUserLoggedController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private GetUserLoggedHandler $handler
    ) {}

    #[Route(path: "/v1/auth/verify-user", name: "v1_auth_verify-user", methods: ["POST"])]
    public function index(): JsonResponse
    {
        $command = new GetUserLoggedCommand(
            userId: $this->authContext->getUserId(),
            verified: $this->authContext->isVerified(),
            sessionId: $this->authContext->getSessionId()
        );

        $user = ($this->handler)($command);

        $response = new GetUserLoggedResponse(
            name: $user->getUserName()->getString(),
            usertag: $user->getUserTag()->getString(),
            img: $user->getImg()->getString(),
            email: $user->getEmail()->getString(),
            userCreatedAt: $user->getUserCreatedAt()->getDateTimeImmutable()
        );

        return $this->json($response, 200);
    }
}
