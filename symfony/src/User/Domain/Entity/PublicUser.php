<?php

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\PublicUserRelation;
use DateTimeImmutable;

class PublicUser
{
    public function __construct(
        public readonly string $userName,
        public readonly string $userTag,
        public readonly string $img,
        public readonly DateTimeImmutable $userCreatedAt,
        public readonly PublicUserRelation $userRelation
    ) {}
}
