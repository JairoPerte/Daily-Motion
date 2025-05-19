<?php

namespace App\Activity\Application\UseCase\DeleteActivity;

class DeleteActivityCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId
    ) {}
}
