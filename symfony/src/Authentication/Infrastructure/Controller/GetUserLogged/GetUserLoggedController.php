<?php

namespace App\Authentication\Infrastructure\Controller\GetUserLogged;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Infrastructure\Security\AuthCookieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Authentication\Domain\Exception\EmailNotVerifiedException;
use App\Authentication\Application\UseCase\GetUserLogged\GetUserLoggedCommand;
use App\Authentication\Application\UseCase\GetUserLogged\GetUserLoggedHandler;
use App\Shared\Infrastructure\Listener\OnKernelException;

class GetUserLoggedController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private GetUserLoggedHandler $handler,
        private AuthCookieManager $authCookieManager
    ) {}

    #[Route(path: "/v1/auth/verify-user", name: "v1_auth_verify-user", methods: ["GET"])]
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
