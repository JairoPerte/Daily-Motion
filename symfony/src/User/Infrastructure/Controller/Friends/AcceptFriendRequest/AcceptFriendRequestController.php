<?php

namespace App\User\Infrastructure\Controller\Friends\AcceptFriendRequest;

use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\User\Application\UseCase\Friends\AcceptFriendRequest\AcceptFriendRequestCommand;
use App\User\Application\UseCase\Friends\AcceptFriendRequest\AcceptFriendRequestHandler;

class AcceptFriendRequestController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private AcceptFriendRequestHandler $handler
    ) {}

    #[Route(path: "/api/user/friends/accept/{usertag}", name: "api_user_friends_accept", methods: ["PUT"])]
    public function index(
        string $usertag
    ): JsonResponse {
        $command = new AcceptFriendRequestCommand(
            id: $this->authContext->getUserId(),
            usertag: $usertag
        );

        ($this->handler)($command);

        return $this->json(["message" => "Friend Request accepted successfully"], 204);
    }
}
