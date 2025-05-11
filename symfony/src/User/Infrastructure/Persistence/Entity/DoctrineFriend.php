<?php

namespace App\User\Infrastructure\Persistence\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity()]
#[ORM\Table(name: "friend")]
class DoctrineFriend
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, name: "sender_id")]
    public string $senderId;

    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, name: "receiver_id")]
    public string $receiverId;

    #[ORM\Column(type: Types::BOOLEAN, name: "pending")]
    public bool $pending;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "accepted_at")]
    public DateTimeImmutable $acceptedAt;
}
