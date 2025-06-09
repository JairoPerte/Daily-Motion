<?php

namespace App\Authentication\Infrastructure\Controller\Sessions\GetSessionsList;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Authentication\Application\UseCase\Sessions\GetSessionsList\GetSessionsListCommand;
use App\Authentication\Application\UseCase\Sessions\GetSessionsList\GetSessionsListHandler;

class GetSessionsListController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private GetSessionsListHandler $handler
    ) {}

    #[Route('/v1/auth/sessions', name: 'v1_auth_sessions', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $command = new GetSessionsListCommand(
            userId: $this->authContext->getUserId(),
            sessionId: $this->authContext->getSessionId()
        );

        $sessions = ($this->handler)($command);

        return $this->json($sessions, 200);
    }
}
