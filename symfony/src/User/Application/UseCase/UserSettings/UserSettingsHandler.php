<?php

namespace App\User\Application\UseCase\UserSettings;

use App\Authentication\Application\Service\Security\PasswordHasher;
use App\User\Domain\Entity\User;
use App\User\Domain\EntityFields\ExistingUserFields;
use App\User\Domain\Exception\ExistingUserException;
use App\User\Domain\Exception\UserNotFoundException;
use App\User\Domain\Exception\WrongPasswordException;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserImg;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserPassword;
use App\User\Domain\ValueObject\UserTag;

class UserSettingsHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasher $passwordHasher
    ) {}

    /**
     * @throws \App\User\Domain\Exception\UserNotFoundException
     */
    public function __invoke(UserSettingsCommand $command): User
    {
        $user = $this->userRepository->findById(new UserId($command->id));

        if ($user) {

            if ($this->passwordHasher->verifyPassword($command->oldPassword, $user->getPassword()->getString())) {

                $userUsertag = $this->userRepository->findByUsertag(new UserTag($command->usertag));

                if ($userUsertag && $userUsertag->getId()->getUuid() != $user->getId()->getUuid()) {
                    throw new ExistingUserException(new ExistingUserFields(true, false));
                }

                $user->update(
                    userName: new UserName($command->name),
                    userTag: new UserTag($command->usertag),
                    password: new UserPassword($this->passwordHasher->hashPassword($command->newPassword)),
                    img: !$command->img ? null : new UserImg($command->img)
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
