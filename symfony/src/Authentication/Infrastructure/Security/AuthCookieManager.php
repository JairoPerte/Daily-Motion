<?php

namespace App\Authentication\Infrastructure\Security;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthCookieManager
{
    public function clearTokenCookie(JsonResponse $response): void
    {
        $response->headers->setCookie(
            Cookie::create('token')->withValue('')->withExpires(0)
        );
    }

    public function setTokenCookie(JsonResponse $response, string $jwt): void
    {
        $response->headers->setCookie(
            Cookie::create('token')
                ->withValue($jwt)
                ->withHttpOnly(true)
                ->withSecure(true)
                ->withPath('/')
                ->withExpires(strtotime('+30 days'))
                ->withSameSite('Strict')
        );
    }
}
