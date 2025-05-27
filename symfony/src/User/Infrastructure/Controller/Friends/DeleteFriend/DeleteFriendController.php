<?php

namespace App\User\Infrastructure\Controller\Friends\DeleteFriend;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Authentication\Application\Service\Security\SessionValidator;
use App\User\Application\UseCase\Friends\DeleteFriend\DeleteFriendCommand;
use App\User\Application\UseCase\Friends\DeleteFriend\DeleteFriendHandler;

class DeleteFriendController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private DeleteFriendHandler $handler
    ) {}

    #[Route(path: "/v1/user/friends/delete/{usertag}", name: "v1_user_friends_delete", methods: ["DELETE"])]
    public function index(
        string $usertag
    ): JsonResponse {
        $command = new DeleteFriendCommand(
            id: $this->authContext->getUserId(),
            sessionId: $this->authContext->getSessionId(),
            verified: $this->authContext->isVerified(),
            usertag: $usertag
        );

        ($this->handler)($command);

        return $this->json(["message" => "Friend deleted successfully"], 204);
    }
}
