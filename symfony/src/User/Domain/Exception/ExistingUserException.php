<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;
use App\User\Domain\EntityFields\ExistingUserFields;

class ExistingUserException extends DailyMotionException
{
    public function __construct(
        private ExistingUserFields $existingUserFields
    ) {
        parent::__construct("There are some fields that already exists in another user");
        $this->httpCode = 409;
    }

    public function getFieldsToResponse(): array
    {
        return [
            "usertag" => $this->existingUserFields->usertag,
            "email" => $this->existingUserFields->email
        ];
    }
}
