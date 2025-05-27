<?php

namespace App\Authentication\Infrastructure\Controller\VerifyEmail;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\Authentication\Infrastructure\Security\AuthCookieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Authentication\Application\UseCase\VerifyEmail\VerifyEmailCommand;
use App\Authentication\Application\UseCase\VerifyEmail\VerifyEmailHandler;

class VerifyEmailController extends AbstractController
{
    public function __construct(
        private VerifyEmailHandler $handler,
        private AuthContext $authContext,
        private AuthCookieManager $authCookieManager
    ) {}

    #[Route(path: "/v1/auth/verify-email/{code}", name: "v1_auth_verify-email", methods: ["POST"])]
    public function index(
        string $code
    ): JsonResponse {
        $command = new VerifyEmailCommand(
            code: $code,
            userId: $this->authContext->getUserId(),
            sessionId: $this->authContext->getSessionId()
        );

        $jwt = ($this->handler)($command);

        $response = $this->json(["message" => "Email code is correct"], 204);
        $this->authCookieManager->setTokenCookie($response, $jwt);
        return $response;
    }
}
