<?php

namespace App\User\Domain\EntityFields;

class BadFormatedUserFields
{
    public function __construct(
        public readonly bool $name,
        public readonly bool $usertag,
        public readonly bool $email,
        public readonly bool $password
    ) {}
}
