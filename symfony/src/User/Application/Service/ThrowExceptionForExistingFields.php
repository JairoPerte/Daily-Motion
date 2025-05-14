<?php

namespace App\User\Application\Service;

use App\User\Domain\EntityFields\ExistingUserFields;
use App\User\Domain\Exception\ExistingUserException;

class ThrowExceptionForExistingFields
{
    public function __construct() {}

    public function __invoke(array $users, string $usertag, string $email): void
    {
        if ($users) {
            $existingUserFields = new ExistingUserFields(
                false,
                false
            );

            foreach ($users as $user) {
                if ($user->usertag == $usertag) {
                    $existingUserFields->usertag = true;
                }
                if ($user->getemail == $email) {
                    $existingUserFields->email = true;
                }
            }

            throw new ExistingUserException($existingUserFields);
        }
    }
}
