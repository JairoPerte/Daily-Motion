<?php

namespace App\User\Infrastructure\Controller\UserFriends;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Application\UseCase\UserFriends\UserFriendsCommand;
use App\User\Application\UseCase\UserFriends\UserFriendsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class UserFriendsController extends AbstractController
{
    public function __construct(
        private UserFriendsHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/api/user/{usertag}/friends", name: "api_user_friends", methods: ["GET"])]
    public function index(
        #[MapQueryString] UserFriendsQuery $query,
        string $usertag

    ): JsonResponse {
        $command = new UserFriendsCommand(
            usertag: $usertag,
            page: $query->page,
            limit: $query->limit,
            visitorId: $this->authContext->getUserId()
        );

        $friends = ($this->handler)($command);

        return $this->json($friends, 200);
    }
}
