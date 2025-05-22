<?php

namespace App\User\Infrastructure\Controller\Friends\UserFriendRequests;

use App\User\Domain\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\User\Application\UseCase\Friends\UserFriendRequests\UserFriendRequestsCommand;
use App\User\Application\UseCase\Friends\UserFriendRequests\UserFriendRequestsHandler;

class UserFriendRequestsController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private UserFriendRequestsHandler $handler,
    ) {}

    #[Route(path: "/v1/user/friends/requests", name: "v1_user_friends_requests", methods: ["GET"])]
    public function index(): JsonResponse
    {
        $command = new UserFriendRequestsCommand(
            userId: $this->authContext->getUserId()
        );

        $users = ($this->handler)($command);

        $users = array_map(
            fn(User $user): UserFriendRequestResponse => new UserFriendRequestResponse(
                name: $user->getUserName()->getString(),
                usertag: $user->getUserTag()->getString(),
                img: $user->getImg()->getString()
            ),
            $users
        );

        return $this->json(["users" => $users], 200);
    }
}
