<?php

namespace App\Activity\Application\UseCase\GetActivity;

class GetActivityCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId
    ) {}
}
