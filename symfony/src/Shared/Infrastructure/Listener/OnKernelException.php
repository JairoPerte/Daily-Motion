<?php

namespace App\Shared\Infrastructure\Listener;

use App\Authentication\Domain\Exception\SessionNotValidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class OnKernelException
{
    public function __construct() {}

    public function onKernelException(ExceptionEvent $exception): void
    {
        $exception->setResponse(new JsonResponse(
            ["message" => $exception->getThrowable()->getMessage()],
            $this->getCode($exception->getThrowable())
        ));
    }

    private function getCode(Throwable $exception): int
    {
        if ($exception->httpCode) {
            return $exception->httpCode;
        }
        if ($exception instanceof SessionNotValidException) {
            return 401;
        }
        return 500;
    }
}
