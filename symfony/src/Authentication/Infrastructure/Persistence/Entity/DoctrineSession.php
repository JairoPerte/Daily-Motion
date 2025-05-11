<?php

namespace App\Authentication\Infrastructure\Persistence\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity()]
#[ORM\Table(name: "session")]
class DoctrineSession
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, name: "id")]
    public string $id;

    #[ORM\Column(type: Types::STRING, name: "user_id")]
    public string $userId;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "created_at")]
    public DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "expires_at")]
    public DateTimeImmutable $expiresAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "last_activity")]
    public DateTimeImmutable $lastActivity;

    #[ORM\Column(type: Types::STRING, name: "user_agent")]
    public string $userAgent;

    #[ORM\Column(type: Types::BOOLEAN, name: "revoked")]
    public bool $revoked;
}
