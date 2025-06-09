<?php

namespace App\User\Infrastructure\Controller\GetUsersList;

use App\User\Application\UseCase\GetUsersList\GetUsersListCommand;
use App\User\Application\UseCase\GetUsersList\GetUsersListHandler;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class GetUsersListController extends AbstractController
{
    public function __construct(
        private GetUsersListHandler $handler,
    ) {}

    #[Route('/v1/users', name: 'v1_user_list', methods: ['GET'])]
    public function index(
        #[MapQueryString] GetUsersListQueryString $queryParameters
    ): JsonResponse {
        $command = new GetUsersListCommand(
            search: $queryParameters->search,
            limit: $queryParameters->limit,
            page: $queryParameters->page
        );

        $users = ($this->handler)($command);

        $response = array_map(
            fn(User $user) => new GetUsersListResponse(
                name: $user->getUserName()->getString(),
                usertag: $user->getUsertag()->getString(),
                img: $user->getImg()->getString(),
                createdAt: $user->getUserCreatedAt()->getDateTimeImmutable()
            ),
            $users
        );

        return $this->json($response, 200);
    }
}
