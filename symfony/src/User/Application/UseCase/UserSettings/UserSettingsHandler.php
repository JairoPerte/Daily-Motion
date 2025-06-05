<?php

namespace App\User\Application\UseCase\UserSettings;

use InvalidArgumentException;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserImg;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserPassword;
use App\User\Domain\EntityFields\ExistingUserFields;
use App\User\Domain\Exception\ExistingUserException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Exception\WrongPasswordException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Authentication\Application\Service\Security\PasswordHasher;
use App\User\Domain\Exception\NoneFieldForUpdate;

class UserSettingsHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher,
    ) {}

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     * @throws \App\User\Domain\Exception\WrongPasswordException
     * @throws \App\User\Domain\Exception\ExistingUserException
     * @throws \App\User\Domain\Exception\NoneFieldForUpdate
     */
    public function __invoke(UserSettingsCommand $command): User
    {
        if (!$command->img && !$command->name && !$command->usertag && !$command->newPassword) {
            throw new NoneFieldForUpdate();
        }

        $user = $this->userRepository->findById(new UserId($command->id));

        if ($user) {

            if ($this->passwordHasher->verifyPassword($command->oldPassword, $user->getPassword()->getString())) {

                if ($command->usertag) {
                    $userUsertag = $this->userRepository->findByUsertag(new UserTag($command->usertag));

                    if ($userUsertag && $userUsertag->getId()->getUuid() != $user->getId()->getUuid()) {
                        throw new ExistingUserException(new ExistingUserFields(true, false));
                    }
                }

                $user->update(
                    userName: $command->name ? new UserName($command->name) : null,
                    userTag: $command->usertag ? new UserTag($command->usertag) : null,
                    password: $command->newPassword ? new UserPassword($this->passwordHasher->hashPassword($command->newPassword)) : null,
                    img: $command->img ?  new UserImg($command->img) : null
                );

                $this->userRepository->save($user);

                return $user;
            } else {
                throw new WrongPasswordException();
            }
        }
        throw new UserNotFoundException();
    }
}
