<?php

namespace App\Shared\Domain\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
