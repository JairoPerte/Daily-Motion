<?php

namespace App\Authentication\Infrastructure\Controller\Register;

use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Authentication\Application\UseCase\Register\RegisterCommand;
use App\Authentication\Application\UseCase\Register\RegisterHandler;
use App\Authentication\Infrastructure\Context\BrowserContext;
use App\Authentication\Infrastructure\Security\AuthCookieManager;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

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
            confirmPassword: $request->confirmPassword,
            email: $request->email,
            userAgent: $userAgent
        );

        $jwt = ($this->handler)($command);

        $response = new JsonResponse(['message' => 'Registrado correctamente']);

        $this->authCookieManager->setTokenCookie($response, $jwt);

        return $response;
    }
}
