<?php

namespace App\Authentication\Infrastructure\Listener;

use App\Authentication\Domain\Exception\JwtNotFoundException;
use App\Authentication\Domain\Exception\JwtNotValidException;
use Throwable;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;

class JwtRequestListener
{
    public function __construct(
        private JwtTokenManagerInterface $tokenManager
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $path = $request->getPathInfo();

        $jwt = $request->cookies->get('token');

        if (!$jwt) {
            if (preg_match('#^/api/auth/(login|register)#', $path)) {
                return;
            }
            throw new JwtNotFoundException();
        }

        try {
            $payload = $this->tokenManager->decodeToken($jwt);
        } catch (Throwable $e) {
            throw new JwtNotValidException();
        }

        $userId = $payload['sub'] ?? null;
        $sessionId = $payload['session_id'] ?? null;

        if (!$userId || !$sessionId) {
            throw new JwtNotValidException();
        }

        $request->attributes->set('userId', $userId);
        $request->attributes->set('sessionId', $sessionId);
    }
}
