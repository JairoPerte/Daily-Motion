<?php

namespace App\Authentication\Infrastructure\Controller\Sessions\RevokeAllSessions;

use App\Authentication\Application\UseCase\Sessions\RevokeAllSessions\RevokeAllSessionsCommand;
use App\Authentication\Application\UseCase\Sessions\RevokeAllSessions\RevokeAllSessionsHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RevokeAllSessionsController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private RevokeAllSessionsHandler $handler
    ) {}

    #[Route(path: '/v1/auth/sessions', name: 'v1_auth_sessions_revoke_all', methods: ["DELETE"])]
    public function index(): JsonResponse
    {
        $command = new RevokeAllSessionsCommand(
            userId: $this->authContext->getUserId(),
            sessionId: $this->authContext->getSessionId()
        );

        ($this->handler)($command);

        return $this->json(["message" => "All sessions revoked"], 204);
    }
}
