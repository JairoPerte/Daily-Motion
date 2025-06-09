<?php

namespace App\Shared\Infrastructure\Listener;

use App\Authentication\Domain\Exception\JwtNotValidException;
use App\Authentication\Domain\Exception\SessionClosedException;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;
use App\Authentication\Domain\ValueObject\SessionId;
use App\Authentication\Infrastructure\Context\AuthContext;
use App\Shared\Domain\Exception\DailyMotionException;
use Throwable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Authentication\Infrastructure\Security\AuthCookieManager;

class OnKernelException
{
    public function __construct(
        private AuthCookieManager $authCookieManager,
        private SessionRepositoryInterface $sessionRepositoryInterface,
        private AuthContext $authContext
    ) {}

    public function onKernelException(ExceptionEvent $exception): void
    {
        $throwable = $exception->getThrowable();

        // Forma del mensaje de error:
        $data = [];
        $data["error"] = get_class($throwable);
        $data["message"] = $throwable->getMessage();
        $fields = $this->getFields($throwable);
        if ($fields) {
            $data["fields"] = $fields;
        }

        // Respuesta
        $response = new JsonResponse(
            $data,
            $this->getCode($throwable)
        );

        // Extra que se quiera hacer con las respuestas (limpiar la cookie p.e)
        if ($throwable instanceof JwtNotValidException || $throwable instanceof SessionClosedException) {
            $this->authCookieManager->clearTokenCookie($response);
            $this->sessionRepositoryInterface->delete(new SessionId($this->authContext->getSessionId()));
        }

        $exception->setResponse($response);
    }

    private function getCode(Throwable $exception): int
    {
        if ($exception instanceof DailyMotionException) {
            return $exception->getHttpCode();
        }
        return 500;
    }

    private function getFields(Throwable $exception): ?array
    {
        if (method_exists($exception, 'getFieldsToResponse')) {
            return $exception->getFieldsToResponse();
        }
        return null;
    }
}
