<?php

namespace App\User\Application\Service;

use App\User\Domain\Entity\User;
use App\User\Domain\EntityFields\ExistingUserFields;
use App\User\Domain\Exception\ExistingUserException;

class ThrowExceptionForExistingFields
{
    public function __construct() {}

    /**
     * @param User[] $users
     * @throws \App\User\Domain\Exception\ExistingUserException
     */
    public function __invoke(array $users, string $usertag, string $email): void
    {
        if ($users) {
            $existingUserFields = new ExistingUserFields(
                false,
                false
            );

            foreach ($users as $user) {
                if ($user->getUserTag()->getString() == $usertag) {
                    $existingUserFields->usertag = true;
                }
                if ($user->getEmail()->getString() == $email) {
                    $existingUserFields->email = true;
                }
            }

            throw new ExistingUserException($existingUserFields);
        }
    }
}
