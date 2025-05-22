<?php

namespace App\User\Infrastructure\Controller\UserProfile;

use App\Authentication\Infrastructure\Context\AuthContext;
use App\User\Application\UseCase\UserProfile\UserProfileCommand;
use App\User\Application\UseCase\UserProfile\UserProfileHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    public function __construct(
        private UserProfileHandler $handler,
        private AuthContext $authContext
    ) {}

    #[Route(path: "/v1/user/{usertag}", name: "v1_user_profile", methods: ["GET"])]
    public function index(
        string $usertag
    ): JsonResponse {
        $command = new UserProfileCommand(
            usertag: $usertag,
            visitorId: $this->authContext->getUserId()
        );

        $publicUser = ($this->handler)($command);

        $response = new UserProfileResponse(
            name: $publicUser->name->getString(),
            usertag: $publicUser->usertag->getString(),
            img: $publicUser->img->getString(),
            userCreatedAt: $publicUser->createdAt->getDateTimeImmutable(),
            relation: $publicUser->relation->value
        );

        return $this->json($response, 200);
    }
}
