<?php

namespace App\Authentication\Infrastructure\Controller\Register;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Authentication\Application\UseCase\Register\RegisterCommand;
use App\Authentication\Application\UseCase\Register\RegisterHandler;
use App\Authentication\Infrastructure\Context\BrowserContext;
use App\Authentication\Infrastructure\Security\AuthCookieManager;

class RegisterController extends AbstractController
{
    public function __construct(
        private RegisterHandler $handler,
        private BrowserContext $browserContext,
        private AuthCookieManager $authCookieManager
    ) {}

    #[Route(path: "/api/auth/register", name: "api_auth_register", methods: ["POST"])]
    public function index(
        #[MapRequestPayload] RegisterRequest $request
    ): JsonResponse {
        $userAgent = $this->browserContext->getUserAgent();

        $command = new RegisterCommand(
            name: $request->name,
            usertag: $request->usertag,
            password: $request->password,
            email: $request->email,
            userAgent: $userAgent
        );

        $jwt = ($this->handler)($command);

        $response = $this->json(['message' => 'User has successfully complete the registration'], 204);
        $this->authCookieManager->setTokenCookie($response, $jwt);
        return $response;
    }
}
