<?php

namespace App\User\Domain\EntityFields;

class ExistingUserFields
{
    public function __construct(
        public bool $usertag,
        public bool $email
    ) {}
}
