<?php

namespace App\Authentication\Infrastructure\Listener;

use App\Authentication\Domain\Exception\SessionNotValidException;
use Throwable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Domain\Security\JwtTokenManagerInterface;
use App\Authentication\Infrastructure\Security\AuthCookieManager;
use App\Authentication\Domain\Repository\SessionRepositoryInterface;

class JwtRequestListener
{
    public function __construct(
        private JwtTokenManagerInterface $tokenManager,
        private SessionRepositoryInterface $sessionRepository,
        private UserRepositoryInterface $userRepository,
        private AuthCookieManager $authCookieManager
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $path = $request->getPathInfo();

        if (preg_match('#^/api/auth/(login|register)#', $path)) {
            return;
        }
        if (!str_starts_with($path, '/api/')) {
            return;
        }

        $jwt = $request->cookies->get('token');

        if (!$jwt) {
            throw new SessionNotValidException("Missing Json Web Token");
        }

        try {
            $payload = $this->tokenManager->decodeToken($jwt);
        } catch (Throwable $e) {
            throw new SessionNotValidException("Invalid Token");
        }

        $userId = $payload['sub'] ?? null;
        $sessionId = $payload['session_id'] ?? null;

        if (!$userId || !$sessionId) {
            throw new SessionNotValidException("Malformed Token");
        }

        $session = $this->sessionRepository->findById($sessionId);
        if (!$session || !$session->isValid() || $session->getUserId() != $userId) {
            $response = new JsonResponse(['error' => 'Session expired or invalid'], 401);
            $this->authCookieManager->clearTokenCookie($response);
            $event->setResponse($response);
            return;
        }

        $user = $this->userRepository->findById($userId);
        if (!$user) {
            $response = new JsonResponse(['error' => 'User not valid'], 401);
            $this->authCookieManager->clearTokenCookie($response);
            $event->setResponse($response);
            return;
        }
        if (!$user->getEmail()->isVerified()) {
            //No se limpia la cookie
            $response = new JsonResponse(['error' => 'User not valid'], 401);
            $event->setResponse($response);
            return;
        }

        $request->attributes->set('user', $user);
        $request->attributes->set('session', $session);
    }
}
