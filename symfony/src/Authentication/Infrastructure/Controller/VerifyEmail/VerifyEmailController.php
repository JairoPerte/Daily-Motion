<?php

namespace App\Authentication\Infrastructure\Controller\VerifyEmail;

use App\Authentication\Application\UseCase\VerifyEmail\VerifyEmailCommand;
use App\Authentication\Application\UseCase\VerifyEmail\VerifyEmailHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Domain\Exception\EmailCodeNotValidException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VerifyEmailController extends AbstractController
{
    public function __construct(
        private VerifyEmailHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/auth/verify-email/{code}", name: "api_auth_verify-email", methods: ["POST"])]
    public function index(
        string $code
    ): JsonResponse {
        $user = $this->authContext->getUser();

        $command = new VerifyEmailCommand(
            code: $code,
            user: $user
        );

        try {
            ($this->handler)($command);
            $response = new JsonResponse(["message" => "El código es correcto."], 200);
        } catch (EmailCodeNotValidException $e) {
            $response = new JsonResponse(["message" => $e->getMessage()], 400);
        } finally {
            return $response;
        }
    }
}
