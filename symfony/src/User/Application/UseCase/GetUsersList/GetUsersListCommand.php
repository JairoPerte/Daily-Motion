<?php

namespace App\User\Application\UseCase\GetUsersList;

class GetUsersListCommand
{
    public function __construct(
        public readonly string $search,
        public readonly int $page,
        public readonly int $limit
    ) {}
}
