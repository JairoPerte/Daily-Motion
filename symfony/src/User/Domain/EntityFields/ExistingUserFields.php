<?php

namespace App\User\Domain\EntityFields;

class ExistingUserFields
{
    public function __construct(
        public readonly bool $userTag,
        public readonly bool $email
    ) {}
}
