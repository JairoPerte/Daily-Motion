<?php

namespace App\User\Application\UseCase\Common;

use App\User\Application\UseCase\Common\PublicUserRelation;
use App\User\Domain\ValueObject\UserCreatedAt;
use App\User\Domain\ValueObject\UserImg;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserTag;

class PublicUser
{
    public function __construct(
        public readonly UserName $name,
        public readonly UserTag $usertag,
        public readonly UserImg $img,
        public readonly UserCreatedAt $createdAt,
        public readonly PublicUserRelation $relation
    ) {}
}
