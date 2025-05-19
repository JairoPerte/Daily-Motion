<?php

namespace App\User\Domain\EntityFields;

class BadFormattedUserFields
{
    public function __construct(
        public readonly bool $name,
        public readonly bool $usertag,
        public readonly bool $email,
        public readonly bool $password
    ) {}
}
