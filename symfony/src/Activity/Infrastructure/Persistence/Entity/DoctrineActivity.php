<?php

namespace App\Activity\Infrastructure\Persistence\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity()]
#[ORM\Table(name: "activity")]
class DoctrineActivity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, name: "id")]
    public string $id;

    #[ORM\Column(type: Types::STRING, name: "category_id")]
    public string $categoryId;

    #[ORM\Column(type: Types::STRING, name: "user_id")]
    public string $userId;

    #[ORM\Column(type: Types::STRING, name: "name")]
    public string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "started_at")]
    public DateTimeImmutable $startedAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "finished_at")]
    public ?DateTimeImmutable $finishedAt;
}
