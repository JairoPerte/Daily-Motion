<?php

namespace App\User\Infrastructure\Persistence\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity()]
#[ORM\Table(name: '"user"')]
class DoctrineUser
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, name: "id")]
    public string $id;

    #[ORM\Column(type: Types::STRING, name: "name")]
    public string $name;

    #[ORM\Column(type: Types::STRING, name: "usertag")]
    public string $usertag;

    #[ORM\Column(type: Types::STRING, name: "email")]
    public string $email;

    #[ORM\Column(type: Types::STRING, name: "password")]
    public string $password;

    #[ORM\Column(type: Types::BOOLEAN, name: "email_verified")]
    public bool $emailVerified;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "email_verified_at")]
    public ?DateTimeImmutable $emailVerifiedAt;

    #[ORM\Column(type: Types::STRING, name: "img")]
    public string $img;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "created_at")]
    public DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::STRING, name: "email_code")]
    public string $emailCode;
}
