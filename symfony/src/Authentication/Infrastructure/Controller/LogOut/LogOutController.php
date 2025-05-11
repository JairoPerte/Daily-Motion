<?php

namespace App\Authentication\Infrastructure\Controller\LogOut;

use App\Authentication\Application\UseCase\LogOut\LogOutCommand;
use App\Authentication\Application\UseCase\LogOut\LogOutHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\Authentication\Infrastructure\Security\AuthCookieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LogOutController  extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private LogOutHandler $handler,
        private AuthCookieManager $cookieManager
    ) {}

    #[Route(path: "/api/auth/logout", name: "api_auth_logout", methods: ["DELETE"])]
    public function index(): JsonResponse
    {
        $session = $this->authContext->getSession();
        $command = new  LogOutCommand(
            session: $session
        );

        ($this->handler)($command);

        $response = $this->json(["message" => "Sesión cerrada correctamente"], 200);
        $this->cookieManager->clearTokenCookie($response);
        return $response;
    }
}
