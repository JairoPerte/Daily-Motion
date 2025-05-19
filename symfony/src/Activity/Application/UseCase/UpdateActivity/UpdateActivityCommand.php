<?php

namespace App\Activity\Application\UseCase\UpdateActivity;

class UpdateActivityCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $name,
        public readonly string $categoryId
    ) {}
}
