<?php

namespace App\User\Domain\Exception;

use App\Shared\Domain\Exception\DailyMotionException;
use App\User\Domain\EntityFields\BadFormatedUserFields;

class BadFormattedUserException extends DailyMotionException
{
    public function __construct(
        private BadFormatedUserFields $badFormatedUserFields
    ) {
        parent::__construct("There are some fields that are bad formatted, please change it");
        $this->httpCode = 400;
    }

    public function getFieldsToResponse(): array
    {
        return [
            "name" => $this->badFormatedUserFields->name,
            "usertag" => $this->badFormatedUserFields->usertag,
            "email" => $this->badFormatedUserFields->email,
            "password" => $this->badFormatedUserFields->password
        ];
    }
}
