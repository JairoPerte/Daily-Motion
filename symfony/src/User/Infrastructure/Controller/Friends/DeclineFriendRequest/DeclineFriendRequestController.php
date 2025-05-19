<?php

namespace App\User\Infrastructure\Controller\Friends\DeclineFriendRequest;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Application\UseCase\Friends\DeclineFriendRequest\DeclineFriendRequestCommand;
use App\User\Application\UseCase\Friends\DeclineFriendRequest\DeclineFriendRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeclineFriendRequestController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private DeclineFriendRequestHandler $handler
    ) {}

    #[Route(path: "/api/user/friends/decline/{usertag}", name: "api_user_friends_decline", methods: ["DELETE"])]
    public function index(
        string $usertag
    ): JsonResponse {
        $command = new DeclineFriendRequestCommand(
            id: $this->authContext->getUserId(),
            usertag: $usertag
        );

        ($this->handler)($command);

        return $this->json(["message" => "Friend Request declined successfully"], 204);
    }
}
