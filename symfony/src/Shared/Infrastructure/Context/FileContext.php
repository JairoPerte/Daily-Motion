<?php

namespace App\Shared\Infrastructure\Context;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class FileContext
{
    public function __construct(private RequestStack $requestStack) {}

    public function getUserProfileImg(): ?UploadedFile
    {
        return $this->requestStack->getCurrentRequest()->files->get("userProfileImg");
    }
}
