<?php

namespace App\Authentication\Infrastructure\Controller\Sessions\RevokeSession;

use App\Authentication\Application\UseCase\Sessions\RevokeSession\RevokeSessionCommand;
use App\Authentication\Application\UseCase\Sessions\RevokeSession\RevokeSessionHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RevokeSessionController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private RevokeSessionHandler $handler
    ) {}

    #[Route(path: "/v1/auth/sessions/{id}", name: "v1_auth_sessions_revoke_one", methods: ["DELETE"])]
    public function index(
        string $id
    ): JsonResponse {
        $command = new RevokeSessionCommand(
            $id,
            $this->authContext->getSessionId(),
            $this->authContext->getUserId()
        );

        ($this->handler)($command);

        return $this->json(["message" => "Session revoked sucessfully"], 204);
    }
}
