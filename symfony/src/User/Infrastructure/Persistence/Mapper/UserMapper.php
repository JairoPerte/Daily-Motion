<?php

namespace App\User\Infrastructure\Persistence\Mapper;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\UserImg;
use App\User\Domain\ValueObject\UserTag;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserEmail;
use App\User\Domain\ValueObject\UserPassword;
use App\User\Domain\ValueObject\UserCreatedAt;
use App\User\Infrastructure\Persistence\Entity\DoctrineUser;

class UserMapper
{
    public function toDomain(DoctrineUser $doctrineUser): User
    {
        return User::toEntity(
            userId: new UserId($doctrineUser->id),
            userName: new UserName($doctrineUser->name),
            userTag: new UserTag($doctrineUser->usertag),
            email: new UserEmail(
                email: $doctrineUser->email,
                verified: $doctrineUser->emailVerified,
                verifiedAt: $doctrineUser->emailVerifiedAt,
                emailCode: $doctrineUser->emailCode
            ),
            password: new UserPassword($doctrineUser->password),
            img: new UserImg($doctrineUser->img),
            userCreatedAt: new UserCreatedAt($doctrineUser->createdAt)
        );
    }

    public function toInfrastructure(User $user, ?DoctrineUser $doctrineUser): DoctrineUser
    {
        if (!$doctrineUser) {
            $doctrineUser = new DoctrineUser();
        }

        $doctrineUser->id = $user->getId()->getUuid();
        $doctrineUser->name = $user->getUserName()->getString();
        $doctrineUser->usertag = $user->getUserTag()->getString();
        $doctrineUser->password = $user->getPassword()->getPassword();
        $doctrineUser->img = $user->getImg()->getString();
        $doctrineUser->email = $user->getEmail()->getString();
        $doctrineUser->emailVerified = $user->getEmail()->isVerified();
        $doctrineUser->emailVerifiedAt = $user->getEmail()->getVerifiedAt();
        $doctrineUser->createdAt = $user->getUserCreatedAt()->getDateTimeImmutable();
        $doctrineUser->emailCode = $user->getEmail()->getEmailCode();

        return $doctrineUser;
    }
}
