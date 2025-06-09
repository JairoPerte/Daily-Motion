<?php

namespace App\Authentication\Infrastructure\Controller\DeleteUser;

use App\Authentication\Application\UseCase\DeleteUser\DeleteUserCommand;
use App\Authentication\Application\UseCase\DeleteUser\DeleteUserHandler;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteUserController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private DeleteUserHandler $handler
    ) {}

    #[Route(path: '/v1/auth/user', name: "v1_auth_user_delete", methods: ["DELETE"])]
    public function index(): JsonResponse
    {
        $command = new DeleteUserCommand(
            idLoggedUser: $this->authContext->getUserId(),
            idSession: $this->authContext->getSessionId()
        );

        ($this->handler)($command);

        return $this->json(["User deleted sucessfully"], 204);
    }
}
