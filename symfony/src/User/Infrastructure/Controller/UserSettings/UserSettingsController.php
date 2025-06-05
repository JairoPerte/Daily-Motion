<?php

namespace App\User\Infrastructure\Controller\UserSettings;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Shared\Infrastructure\Context\FileContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\Shared\Infrastructure\FileProcessor\FileProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\User\Application\UseCase\UserSettings\UserSettingsCommand;
use App\User\Application\UseCase\UserSettings\UserSettingsHandler;
use App\User\Infrastructure\Controller\UserSettings\UserSettingsRequest;
use App\User\Infrastructure\Controller\UserSettings\UserSettingsResponse;
use Throwable;

class UserSettingsController extends AbstractController
{
    public function __construct(
        private AuthContext $authContext,
        private UserSettingsHandler $handler,
        private FileContext $fileContext,
        private FileProcessor $fileProcessor
    ) {}

    #[Route(path: "/v1/user/settings", name: "v1_user_settings", methods: ["POST"])]
    public function index(
        Request $request,
    ): JsonResponse {
        $requestObj = new UserSettingsRequest(
            name: $request->request->get("name"),
            usertag: $request->request->get("usertag"),
            newPassword: $request->request->get("newPassword"),
            oldPassword: $request->request->get("oldPassword")
        );

        $imgProfile = $this->fileContext->getUserProfileImg();

        $imgURL = null;
        if ($imgProfile) {
            $imgURL = $this->fileProcessor->uploadProfileIcon(
                imgProfile: $imgProfile,
                userId: $this->authContext->getUserId()
            );
        }

        $command = new UserSettingsCommand(
            id: $this->authContext->getUserId(),
            sessionId: $this->authContext->getSessionId(),
            name: $requestObj->name,
            usertag: $requestObj->usertag,
            newPassword: $requestObj->newPassword,
            oldPassword: $requestObj->oldPassword,
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

        return $this->json($response, 201);
    }
}
