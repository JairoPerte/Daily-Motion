<?php

namespace App\User\Infrastructure\Controller\Friends\DeleteFriend;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Application\UseCase\Friends\DeleteFriend\DeleteFriendCommand;
use App\User\Application\UseCase\Friends\DeleteFriend\DeleteFriendHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteFriendController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private DeleteFriendHandler $handler
    ) {}

    #[Route(path: "/api/user/friends/delete/{usertag}", name: "api_user_friends_delete", methods: ["DELETE"])]
    public function index(
        string $usertag
    ): JsonResponse {
        $command = new DeleteFriendCommand(
            id: $this->authContext->getUserId(),
            usertag: $usertag
        );

        ($this->handler)($command);

        return $this->json(["message" => "Friend deleted successfully"], 204);
    }
}
