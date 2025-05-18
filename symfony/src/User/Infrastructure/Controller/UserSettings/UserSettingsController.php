<?php

namespace App\User\Infrastructure\Controller\UserSettings;

use Symfony\Component\Routing\Annotation\Route;
use App\Shared\Infrastructure\Context\FileContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\User\Application\UseCase\UserSettings\UserSettingsCommand;
use App\User\Application\UseCase\UserSettings\UserSettingsHandler;
use App\User\Infrastructure\Controller\UserSettings\UserSettingsResponse;

class UserSettingsController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private UserSettingsHandler $handler,
        private FileContext $fileContext
    ) {}

    #[Route(path: "/api/user/settings", name: "api_user_settings", methods: ["PUT"])]
    public function index(
        #[MapRequestPayload] UserSettingsRequest $request
    ): JsonResponse {
        $imgProfile = $this->fileContext->getUserProfileImg();

        $imgURL = null;
        if ($imgProfile) {
            //Debería de guardar la img de alguna manera buscar
        }

        $command = new UserSettingsCommand(
            id: $this->authContext->getUserId(),
            sessionId: $this->authContext->getSessionId(),
            name: $request->name,
            usertag: $request->usertag,
            newPassword: $request->newPassword,
            oldPassword: $request->oldPassword,
            img: $imgURL
        );

        $user = ($this->handler)($command);

        $response = new UserSettingsResponse(
            name: $user->getUserName()->getString(),
            usertag: $user->getUserTag()->getString(),
            email: $user->getEmail()->getString(),
            img: $user->getImg()->getString(),
            userCreatedAt: $user->getUserCreatedAt()->getDateTimeImmutable()
        );

        return $this->json($response, 200);
    }
}
