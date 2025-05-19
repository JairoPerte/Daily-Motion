<?php

namespace App\User\Infrastructure\Controller\Friends\SendFriendRequest;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Application\UseCase\Friends\SendFriendRequest\SendFrienRequestCommand;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\User\Application\UseCase\Friends\SendFriendRequest\SendFriendRequestHandler;

class SendFriendRequestController extends AbstractController
{
    public function __construct(
        private SendFriendRequestHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/user/friends/send/{usertag}", name: "api_user_friends_send", methods: ["POST"])]
    public function index(
        string $usertag
    ): JsonResponse {
        $command = new SendFrienRequestCommand(
            id: $this->authContext->getUserId(),
            usertag: $usertag
        );

        ($this->handler)($command);

        return $this->json(["message" => "Friend Request sent successfully"], 204);
    }
}
