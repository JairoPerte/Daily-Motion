<?php

namespace App\Authentication\Infrastructure\Controller\LogIn;

use Throwable;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\BrowserContext;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Authentication\Application\UseCase\LogIn\LogInCommand;
use App\Authentication\Application\UseCase\LogIn\LogInHandler;
use App\Authentication\Infrastructure\Security\AuthCookieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogInController extends AbstractController
{
    public function __construct(
        private LogInHandler $handler,
        private BrowserContext $browserContext,
        private AuthCookieManager $authCookieManager
    ) {}

    #[Route(path: "/api/auth/login", name: "api_auth_login", methods: ["POST"])]
    public function index(
        #[MapRequestPayload()] LogInRequest $request
    ): JsonResponse {
        $command = new LogInCommand(
            email: $request->email,
            password: $request->password,
            userAgent: $this->browserContext->getUserAgent()
        );

        $jwt = ($this->handler)($command);

        $response = new JsonResponse(['message' => 'User has been successfully login'], 201);
        $this->authCookieManager->setTokenCookie($response, $jwt);
        return $response;
    }
}
