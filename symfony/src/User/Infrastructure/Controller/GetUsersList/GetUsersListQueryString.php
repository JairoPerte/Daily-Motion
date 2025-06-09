<?php

namespace App\User\Infrastructure\Controller\GetUsersList;

class GetUsersListQueryString
{
    public function __construct(
        public readonly string $search,
        public readonly int $page,
        public readonly int $limit
    ) {}
}
