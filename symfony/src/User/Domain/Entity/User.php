<?php

namespace App\User\Domain\Entity;

use App\User\Domain\EntityFields\BadFormattedUserFields;
use App\User\Domain\Exception\BadFormattedUserException;
use App\User\Domain\ValueObject\UserCreatedAt;
use App\User\Domain\ValueObject\UserEmail;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserImg;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserPassword;
use App\User\Domain\ValueObject\UserTag;

class User
{
    private function __construct(
        private UserId $userId,
        private UserName $userName,
        private UserTag $userTag,
        private UserEmail $email,
        private UserPassword $password,
        private UserImg $img,
        private UserCreatedAt $userCreatedAt
    ) {
        if (!$userName->isValid() || !$userTag->isValid() || !$email->isValid() || !$password->isValid()) {
            throw new BadFormattedUserException(
                badFormatedUserFields: new BadFormattedUserFields(
                    name: $userName->isValid(),
                    usertag: $userTag->isValid(),
                    email: $email->isValid(),
                    password: $password->isValid()
                )
            );
        }
    }

    public static function create(
        UserId $userId,
        UserName $userName,
        UserTag $userTag,
        string $email,
        UserPassword $password
    ): self {
        return new self(
            userId: $userId,
            userName: $userName,
            userTag: $userTag,
            email: UserEmail::newAccount($email),
            password: $password,
            img: UserImg::imgDefault(),
            userCreatedAt: UserCreatedAt::newUser()
        );
    }

    public static function toEntity(
        UserId $userId,
        UserName $userName,
        UserTag $userTag,
        UserEmail $email,
        UserPassword $password,
        UserImg $img,
        UserCreatedAt $userCreatedAt
    ): self {
        return new self(
            userId: $userId,
            userName: $userName,
            userTag: $userTag,
            email: $email,
            password: $password,
            img: $img,
            userCreatedAt: $userCreatedAt
        );
    }

    public function update(
        UserName $userName,
        UserTag $userTag,
        UserPassword $password,
        UserImg $img,
    ): void {
        $this->userName = $userName;
        $this->password = $password;
        $this->userTag = $userTag;
        $this->img = $img;
    }

    public function getId(): UserId
    {
        return $this->userId;
    }

    public function getUserName(): UserName
    {
        return $this->userName;
    }

    public function getUserTag(): UserTag
    {
        return $this->userTag;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPassword(): UserPassword
    {
        return $this->password;
    }

    public function getImg(): UserImg
    {
        return $this->img;
    }

    public function getUserCreatedAt(): UserCreatedAt
    {
        return $this->userCreatedAt;
    }
}
